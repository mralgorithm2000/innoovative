<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SourceApiTest extends TestCase
{
    /**
     * Test if the /api/sources endpoint returns a successful response.
     *
     * @return void
     */
    public function test_get_sources_success()
    {
        // Send a GET request to the /api/sources endpoint
        $response = $this->getJson('/api/sources');

        // Assert that the response is successful (HTTP 200 OK)
        $response->assertStatus(200);

        // Assert that the "Content" is correct
        $response->assertJsonStructure([
            'Content' => [
                'data' => [
                    '*' => [
                        'source',
                    ],
                ],
            ],
        ]);

        // Assert that the "error" field is false
        $response->assertJsonFragment([
            'error' => false
        ]);
    }

    /**
     * Test pagination (optional).
     *
     * @return void
     */
    public function test_source_pagination()
    {
        // Send a GET request to the first page of sources
        $response = $this->getJson('/api/sources?page=2');

        // Assert that the response contains pagination information
        $response->assertJsonFragment([
            'current_page' => 2,
        ]);
    }
}
