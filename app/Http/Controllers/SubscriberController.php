<?php

namespace App\Http\Controllers;

use App\Adapters\MailerLiteAdapter;
use App\Http\Middleware\EnsureApiKeyExist;
use App\Models\ApiKey;
use App\Services\MailerLite\MailerLiteException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubscriberController extends Controller
{
    public function __construct()
    {
        $this->middleware(EnsureApiKeyExist::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('subscribers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('subscribers.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $api = ApiKey::first();
        if (!$api) {
            throw new BadRequestException("No API Key");
        }

        $adapter = new MailerLiteAdapter($api->key);
        try {
            $subscriber = $adapter->findSubscriber($id);

            return view('subscribers.edit', ['subscriber' => $subscriber]);
        } catch (MailerLiteException $exception) {
            if ($exception->getCode() === 404) {
                throw new NotFoundHttpException($exception->getMessage());
            }

            throw new BadRequestException($exception->getMessage());
        }
    }
}
