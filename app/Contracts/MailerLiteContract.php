<?php

namespace App\Contracts;

use App\Services\HttpResponseModel;
use App\Services\MailerLite\SubscriberModel;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

interface MailerLiteContract
{
    public function listSubscribers(
        int $length,
        string $cursor
    ): HttpResponseModel;

    public function createSubscriber(SubscriberModel $subscriber
    ): HttpResponseModel;

    public function updateSubscriber(
        string $subscriberId,
        SubscriberModel $subscriber
    ): HttpResponseModel;

    public function getSingleSubscriber(string $subscriberId
    ): HttpResponseModel;

    public function getTotalNumberOfSubscribers(): HttpResponseModel;

    public function deleteSubscriber(string $subscriberId): HttpResponseModel;
}
