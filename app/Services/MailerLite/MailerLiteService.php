<?php

namespace App\Services\MailerLite;

use App\Contracts\MailerLiteContract;
use App\Services\HttpResponseModel;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class MailerLiteService implements MailerLiteContract
{
    private string $apiKey;
    private string $baseUrl;

    private PendingRequest $request;
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = config("services.mailerlite.base_url");

        $this->request = Http::baseUrl($this->baseUrl)->withToken($this->apiKey);
    }

    public function listSubscribers(): PromiseInterface|Response
    {
        $response = $this->request->get("/subscribers");

        return $response;
    }

    public function createSubscriber(SubscriberModel $subscriberModel): HttpResponseModel
    {
        // TODO: Implement createSubscriber() method.
        return new HttpResponseModel();
    }

    public function updateSubscriber(string $subscriberId, SubscriberModel $subscriber): HttpResponseModel
    {
        // TODO: Implement updateSubscriber() method.
        return new HttpResponseModel();
    }

    public function getSingleSubscriber(string $subscriberId): HttpResponseModel
    {
        // TODO: Implement getSingleSubscriber() method.
        return new HttpResponseModel();
    }

    public function getTotalNumberOfSubscribers(): PromiseInterface|Response
    {
        return $this->request->get("/subscribers?limit=0");
    }

    public function deleteSubscriber(string $subscriberId): HttpResponseModel
    {
        // TODO: Implement deleteSubscriber() method.
        return new HttpResponseModel();
    }

    public function forgetSubscriber(string $subscriberId): HttpResponseModel
    {
        // TODO: Implement forgetSubscriber() method.
        return new HttpResponseModel();
    }
}
