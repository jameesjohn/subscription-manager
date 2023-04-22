<?php

namespace App\Contracts;

use App\Services\HttpResponseModel;
use App\Services\MailerLite\SubscriberModel;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

interface MailerLiteContract
{
    public function listSubscribers(): PromiseInterface|Response;

    public function createSubscriber(SubscriberModel $subscriberModel): HttpResponseModel;

    public function updateSubscriber(string $subscriberId, SubscriberModel $subscriber): HttpResponseModel;

    public function getSingleSubscriber(string $subscriberId): HttpResponseModel;

    public function getTotalNumberOfSubscribers(): PromiseInterface|Response;

    public function deleteSubscriber(string $subscriberId): HttpResponseModel;

    public function forgetSubscriber(string $subscriberId): HttpResponseModel;
}
