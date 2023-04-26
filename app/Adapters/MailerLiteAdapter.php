<?php

namespace App\Adapters;

use App\Contracts\MailerLiteContract;
use App\Services\MailerLite\MailerLiteException;
use App\Services\MailerLite\MailerLiteService;
use App\Services\MailerLite\MockMailerLiteService;
use App\Services\MailerLite\SubscriberModel;
use Illuminate\Support\Facades\App;

class MailerLiteAdapter
{
    private MailerLiteContract $service;

    public function __construct(string $apiKey)
    {
        if (App::environment() == 'testing') {
            $this->service = new MockMailerLiteService($apiKey);
        } else {
            $this->service = new MailerLiteService($apiKey);
        }
    }

    public function checkValidKey(): bool
    {
        $response = $this->service->getTotalNumberOfSubscribers();

        return !$response->status;
    }

    public function getTotalSubscribers(): int
    {
        $response = $this->service->getTotalNumberOfSubscribers();

        return $response->data['total'];
    }

    /**
     * @throws MailerLiteException
     */
    public function getSubscribers(int $length = 10, $cursor = ""): array
    {
        $response = $this->service->listSubscribers($length, $cursor);

        if ($response->status) {
            $body = $response->data;
            $subscribers = [];

            foreach ($body['data'] as $datum) {
                $subscriber = SubscriberModel::newFromMailerLiteArray($datum);
                $subscribers[] = $subscriber->toArray();
            }

            return [
                'subscribers' => $subscribers,
                'pagination' => $body['links']
            ];
        }

        throw new MailerLiteException(
            "unable to list subscribers",
            $response->statusCode,
            $response->data
        );
    }

    public function searchByEmail(string $email): array
    {
        $response = $this->service->getSingleSubscriber($email);

        if ($response->status) {
            $result = $response->data;
            $subscriber = SubscriberModel::newFromMailerLiteArray(
                $result['data']
            );
            return [
                'subscribers' => [$subscriber->toArray()],
                'pagination' => []
            ];
        }

        return [
            'subscribers' => [],
            'pagination' => []
        ];
    }

    /**
     * @throws MailerLiteException
     */
    public function createSubscriber($name, $email, $country): SubscriberModel
    {
        $subscriberModel = new SubscriberModel();
        $subscriberModel->name = $name;
        $subscriberModel->email = $email;
        $subscriberModel->country = $country;

        $response = $this->service->createSubscriber($subscriberModel);

        if ($response->status) {
            $result = $response->data;
            return SubscriberModel::newFromMailerLiteArray($result['data']);
        }

        throw new MailerLiteException(
            "Unable to create subscriber",
            $response->statusCode,
            $response->data
        );
    }

    /**
     * @throws MailerLiteException
     */
    public function findSubscriber(string $id): SubscriberModel
    {
        $response = $this->service->getSingleSubscriber($id);

        if ($response->status) {
            $result = $response->data;
            return SubscriberModel::newFromMailerLiteArray($result['data']);
        }

        throw new MailerLiteException(
            'Unable to find subscriber',
            $response->statusCode,
            $response->data,
        );
    }

    /**
     * @throws MailerLiteException
     */
    public function updateSubscriber(
        string $id,
        string $name,
        string $country
    ): SubscriberModel
    {
        $subscriber = $this->findSubscriber($id);
        $subscriber->name = $name;
        $subscriber->country = $country;

        $response = $this->service->updateSubscriber($id, $subscriber);

        if ($response->status) {
            $result = $response->data;

            return SubscriberModel::newFromMailerLiteArray($result['data']);
        }

        throw new MailerLiteException(
            'Unable to update subscriber',
            $response->statusCode,
            $response->data
        );
    }

    /**
     * @throws MailerLiteException
     */
    public function deleteSubscriber(string $id): bool
    {
        $response = $this->service->deleteSubscriber($id);

        if ($response->status) {
            return true;
        }

        throw new MailerLiteException(
            'Unable to delete subscriber',
            $response->statusCode,
            $response->data
        );
    }
}
