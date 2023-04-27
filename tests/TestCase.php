<?php

namespace Tests;

use Database\Seeders\ApiKeySeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ApiKeySeeder::class);
    }
}
