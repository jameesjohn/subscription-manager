<?php

namespace App\Services\MailerLite;

use App\Contracts\MailerLiteContract;
use App\Services\HttpResponseModel;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class MockMailerLiteService implements MailerLiteContract
{

    public function __construct($apiKey)
    {
    }

    private function generateFakeSubscriber(
        SubscriberModel $subscriberModel = null
    ): array {
        return [
            'email' => $subscriberModel ?
                $subscriberModel->email : fake()->email,
            'subscribed_at' => fake()->date,
            'id' => $subscriberModel ? $subscriberModel->id : fake()->uuid(),

            'fields' => [
                'name' => $subscriberModel ?
                    $subscriberModel->name : fake()->name,
                'country' => $subscriberModel ?
                    $subscriberModel->country : fake()->country,
            ]
        ];
    }

    public function listSubscribers(
        int $length,
        string $cursor
    ): HttpResponseModel {
        $subscribers = [];
        for ($i = 0; $i < $length; $i++) {
            $subscribers[] = $this->generateFakeSubscriber();
        }

        return new HttpResponseModel(true, 200, [
            'data' => $subscribers,
            'links' => [
                'prev' => fake()->url,
                'next' => fake()->url
            ]
        ]);
    }

    public function createSubscriber(SubscriberModel $subscriber
    ): HttpResponseModel {
        return new HttpResponseModel(true, 201, [
            'data' => $this->generateFakeSubscriber($subscriber)
        ]);
    }

    public function updateSubscriber(
        string $subscriberId,
        SubscriberModel $subscriber
    ): HttpResponseModel {

        return new HttpResponseModel(true, 200, [
            'data' => $this->generateFakeSubscriber($subscriber)
        ]);
    }

    public function getSingleSubscriber(string $subscriberId): HttpResponseModel
    {
        return new HttpResponseModel(true, 200, [
            'data' => $this->generateFakeSubscriber()
        ]);
    }

    public function getTotalNumberOfSubscribers(): HttpResponseModel
    {
        return new HttpResponseModel(
            true, 200, ['total' => 100]);
    }

    public function deleteSubscriber(string $subscriberId): HttpResponseModel
    {
        return new HttpResponseModel(true, 204, []);
    }
}
