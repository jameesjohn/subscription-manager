<?php

namespace App\Utilities;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiUtility
{

    const RESULTS_PER_PAGE = 30;

    const RESPONDER = [
        'status' => false,
        'message' => '',
        'data' => [],
    ];

    /**
     * Returns a success [200] response
     * @param string $msg
     * @param array $data
     * @return JsonResponse
     */
    public static function success(string $msg, array $data = []): JsonResponse
    {
        $pagination_aware_data = self::apiPaginationFactory($data);
        $payload = $pagination_aware_data['payload'];
        $pagination = $pagination_aware_data['pagination'];

        $responder = self::RESPONDER;
        $responder['status'] = true;
        $responder['message'] = $msg;
        $responder['data'] = $payload;
        $responder['pagination'] = $pagination ?? [];

        return Response::json($responder);
    }

    /**
     * Returns a failure [4xx,5xx] response
     * @param null $exception
     * @param string $msg
     * @param array $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    public
    static function failure($exception = null, string $msg='', array $errors = [], int $statusCode = 500): JsonResponse
    {
        $responder = self::RESPONDER;
        $responder['status'] = false;
        $responder['message'] = $msg;
        $responder['errors'] = $errors;

        if (!is_null($exception)) {
            $exceptionFormat = "\n" . config('app.name') . "-EXCEPTION \nMESSAGE:: %s \nFILE:: %s \nLINE::%s \n\n";

            Log::info(sprintf($exceptionFormat,
                !empty(trim($exception->getMessage())) ? $exception->getMessage() : get_class($exception),
                $exception->getFile(),
                $exception->getLine()
            ));
        }

        return Response::json($responder, $statusCode);
    }

    /**
     * Separates eloquent paginated results
     * into a data and pagination data
     * @param $data
     * @return array
     */
    public
    static function apiPaginationFactory($data): array
    {
        $pagination = [];

        // to cater for pagination data
        if (is_object($data)) {
            switch (get_class($data)) {
                case LengthAwarePaginator::class:
                    $pagination = $data->toArray();
                    $payload = $pagination['data'];
                    unset($pagination['data']);
                    break;

                default:
                    $payload = $data;
                    break;
            }
        } else {
            $payload = $data;
        }

        return [
            'payload' => $payload,
            'pagination' => $pagination
        ];
    }

    /**
     * Aborts a request
     * @param int|null $statusCode
     * @param string|null $message
     * @return JsonResponse
     */
    public
    static function abort(?int $statusCode, ?string $message): JsonResponse
    {
        $msg = $message ?: 'Malformed request';
        $status_code = $statusCode ?: 400;
        return self::failure(null, $msg, [], $status_code)->send();
    }

    /**
     * Aborts a request
     * @param null $message
     * @param array $errors
     * @return JsonResponse
     */
    public
    static function badRequest($message = null, array $errors=[]): JsonResponse
    {
        $message = $message ?? 'Bad Request';

        return self::failure(
            new BadRequestException($message),
            $message,
            $errors,
            400
        );
    }

    /**
     * @param null $message
     * @return JsonResponse
     */
    public
    static function notFound($message = null): JsonResponse
    {
        return self::failure(
            new NotFoundHttpException($message ?? 'Resource not found'),
            $message ?? 'Resource not found',
            [],
            404
        );
    }

    public static function applicationKey(): string
    {
        return request()->bearerToken();
    }

    /**
     * @param null $message
     * @return JsonResponse
     */
    public static function unauthorized($message=null): JsonResponse
    {
        return self::failure(
            new UnauthorizedException,
            $message ?? 'Unauthorized access', [], 401
        );
    }

    /**
     * @param null $message
     * @return JsonResponse
     */
    public static function conflict($message=null): JsonResponse
    {
        return self::failure(
            new ConflictHttpException($message),
            $message ?: 'Item already exists', [], 409
        );
    }

    /**
     * @param null $message
     * @return JsonResponse
     */
    public static function forbidden($message = null): JsonResponse
    {
        return self::failure(
            new UnauthorizedException,
            $message ?? 'You do not have access to this content', [], 403
        );
    }

    /**
     * @param null $message
     * @return JsonResponse
     */
    public static function upgrade($message = null): JsonResponse
    {
        return self::failure(
            new Exception(),
            $message ?: 'You need to upgrade your account to access this feature', [], 426
        );
    }

    /**
     * @param $message
     * @param $errors
     * @return JsonResponse
     */
    public static function validation($message, $errors=[]): JsonResponse
    {
        return self::failure(
            ValidationException::withMessages($errors),
            $message, $errors, 422
        );
    }
}
