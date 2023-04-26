<?php

namespace App\Services\MailerLite;

use App\Contracts\MailerLiteContract;
use App\Services\HttpResponseModel;
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
        $this->baseUrl = config("services.mailerlite.base_url") ?: "https://connect.mailerlite.com/api";

        $this->request = Http::baseUrl($this->baseUrl)
            ->withToken($this->apiKey);
    }

    public function listSubscribers(
        int $length,
        string $cursor
    ): HttpResponseModel {
        return $this->toResponseModel(
            $this->request->get(
                "/subscribers",
                ['limit' => $length, 'cursor' => $cursor]
            )
        );
    }

    public function createSubscriber(SubscriberModel $subscriber
    ): HttpResponseModel {
        return $this->toResponseModel(
            $this->request->post(
                "/subscribers",
                $subscriber->toMailerLiteArray()
            )
        );
    }

    public function updateSubscriber(
        string $subscriberId,
        SubscriberModel $subscriber
    ): HttpResponseModel {
        return $this->toResponseModel(
            $this->request->put(
                "/subscribers/{$subscriberId}",
                $subscriber->toMailerLiteArray()
            )
        );
    }

    public function getSingleSubscriber(string $subscriberId): HttpResponseModel
    {
        return $this->toResponseModel(
            $this->request->get("/subscribers/{$subscriberId}"));
    }

    public function getTotalNumberOfSubscribers(): HttpResponseModel
    {
        return $this->toResponseModel(
            $this->request->get("/subscribers", ["limit" => 0]));
    }

    public function deleteSubscriber(string $subscriberId): HttpResponseModel
    {
        return $this->toResponseModel(
            $this->request->delete("/subscribers/{$subscriberId}"));
    }

    private function toResponseModel(
        Response $response,
    ): HttpResponseModel {
        return new HttpResponseModel(
            !$response->failed(),
            $response->status(),
            $response->json(),
        );
    }

}
