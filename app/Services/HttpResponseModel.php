<?php

namespace App\Services;

class HttpResponseModel
{
    public bool $status;
    public int $statusCode;
    public string $message;
    public string $error;
    public array $errors;
    public ?\Exception $exception;
    public $data;
    public $meta;

    const SUCCESS_STATUS_CODES = [200, 201, 204,];

    public function __construct(bool $status, int $statusCode, $data, string $message,
                                string $error = '', array $errors = [], $exception=null, array $meta=[])
    {
        $this->status = $status;
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->error = $error;
        $this->errors = $errors;
        $this->data = $data;
        $this->exception = $exception;
        $this->meta = json_decode(json_encode($meta));
    }

    public function setStatus(int $statusCode): self
    {
        $this->status = false;
        $this->statusCode = 400;

        if (in_array($statusCode, self::SUCCESS_STATUS_CODES) ) {
            $this->status = true;
            $this->statusCode = $statusCode;
        }

        return $this;
    }

    public function setData($data): self
    {
        if (is_array($data)) {
            $this->data = json_decode( json_encode($data) );
        }
        else {
            $this->data = $data;
        }

        return $this;
    }

    public static function empty(): HttpResponseModel
    {
        return new HttpResponseModel(
            false,
            100,
            null,
            'Empty Response Model',
        );
    }

    public static function unimplemented(): HttpResponseModel
    {
        return new HttpResponseModel(
            false,
            505,
            null,
            'Method is not implemented!',
        );
    }

    public static function fill(int $statusCode, string $message, mixed $data): HttpResponseModel
    {
        return new HttpResponseModel(
            $statusCode >= 200 && $statusCode < 300,
            $statusCode,
            $data,
            $message,
        );
    }

    public function toArray(): array
    {
        return [
            "status" => $this->status,
            "statusCode" => $this->statusCode,
            "message" => $this->message,
            "error" => $this->error,
            "errors" => $this->errors,
            "exception" => $this->exception,
            "data" => $this->data,
            "meta" => $this->meta,
        ];
    }
}
