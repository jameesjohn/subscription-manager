<?php

namespace App\Adapters;

use App\Contracts\MailerLiteContract;
use App\Services\MailerLite\MailerLiteService;

class MailerLiteAdapter
{
    private MailerLiteContract $service;
    public function __construct(string $apiKey)
    {
        $this->service = new MailerLiteService($apiKey);
    }

    public function checkValidKey(): bool
    {
        $response = $this->service->getTotalNumberOfSubscribers();

        return !$response->unauthorized();
    }
}
