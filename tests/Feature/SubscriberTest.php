<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_should_list_subscribers_for_data_tables()
    {
        $response = $this->get('/api/subscribers/');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'search',
            'length',
            'recordsFiltered',
            'recordsTotal',
            'start',
            'isNext',
            'isPrev',
            'data',
            'pagination'
        ]);
    }

    public function test_should_create_subscriber()
    {
        $response = $this->post('/api/subscribers/', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'country' => 'Nigeria'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'date_subscribed',
            'time_subscribed'
        ]);
    }

    public function test_should_validate_subscriber_creation()
    {
        $response = $this->post('/api/subscribers/', [
            'name' => 'Test User',
            'email' => 'test@lol',
            'country' => 'Nigeria'
        ]);

        $response->assertStatus(422);

        $response = $this->post('/api/subscribers/', [
            'email' => 'test@lol',
            'country' => 'Nigeria'
        ]);

        $response->assertStatus(422);

        $response = $this->post('/api/subscribers/', [
            'name' => 'Test User',
            'email' => 'test@lol',
        ]);

        $response->assertStatus(422);
    }

    public function test_should_update_subscriber()
    {
        $response = $this->put('/api/subscribers/2', [
            'name' => 'Another Subscriber',
            'country' => 'Nigeria'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'date_subscribed',
            'time_subscribed'
        ]);
    }

    public function test_should_delete_subscriber()
    {
        $response = $this->delete('/api/subscribers/2', [
            'name' => 'Another Subscriber',
            'country' => 'Nigeria'
        ]);

        $response->assertStatus(204);
    }
}
