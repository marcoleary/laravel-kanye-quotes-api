<?php

namespace Tests\Feature;

use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;

class RefreshTest extends TestCase
{
    public function testRequestWithoutApiTokenReturnsUnauthorized(): void
    {
        $response = $this->get('/api/refresh');

        $response->assertUnauthorized();
    }

    public function testRequestWithApiTokenReturnsOk(): void
    {
        $response = $this->get('/api/refresh', ['x-api-key' => env('APP_API_KEY')]);

        $response->assertOk();
    }

    public function testRequestWithApiTokenReturnsCorrectJsonStructure(): void
    {
        $response = $this->get('/api/refresh', ['x-api-key' => env('APP_API_KEY')]);

        $response->assertJsonStructure(['data']);
    }

    public function testTwoConsecutiveRequestsReturnDifferentQuotes(): void
    {
        $response1 = $this->get('/api/refresh', ['x-api-key' => env('APP_API_KEY')]);
        $response2 = $this->get('/api/refresh', ['x-api-key' => env('APP_API_KEY')]);

        // Fun fact: there's an approximately 1 in 23 billion chance of this being true
        // I'll take my chances for the sake of this test...
        assertNotEquals($response1->json(), $response2->json());
    }
}
