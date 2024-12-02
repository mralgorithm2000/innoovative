<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NewsApiTest extends TestCase
{
   /**
     * Test if the /api/sources endpoint returns a successful response.
     *
     * @return void
     */
    public function test_get_news_success()
    {
        // Send a GET request to the /api/sources endpoint
        $response = $this->getJson('/api/news');

        // Assert that the response is successful (HTTP 200 OK)
        $response->assertStatus(200);

        // Assert that the "Content" is correct
        $response->assertJsonStructure([
            'Content' => [
                'data' => [
                    '*' => [
                        "id",
                        "title",
                        "url",
                        "source",
                        "description",
                        "author",
                        "created_at",
                        "updated_at"
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
    public function test_news_pagination()
    {
        // Send a GET request to the first page of sources
        $response = $this->getJson('/api/news?page=2');

        // Assert that the response contains pagination information
        $response->assertJsonFragment([
            'current_page' => 2,
        ]);
    }
}
