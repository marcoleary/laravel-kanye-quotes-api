<?php

namespace Tests\Feature;

use Tests\TestCase;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

class QuotesTest extends TestCase
{
    public function testRequestWithoutApiTokenReturnsUnauthorized(): void
    {
        $response = $this->get('/api/quotes');

        $response->assertUnauthorized();
    }

    public function testDefaultRequestWithApiTokenReturnsOk(): void
    {
        $response = $this->get('/api/quotes', ['x-api-key' => env('APP_API_KEY')]);
        $response->assertOk();
    }

    public function testDefaultRequestWithApiTokenReturnsCorrectJsonStructure(): void
    {
        $response = $this->get('/api/quotes', ['x-api-key' => env('APP_API_KEY')]);
        $response->assertJsonStructure(['data']);
    }

    public function testDefaultRequestWithApiTokenReturnsFiveQuotes(): void
    {
        $response = $this->get('/api/quotes', ['x-api-key' => env('APP_API_KEY')]);
        $json = json_decode($response->getContent(), true, flags: JSON_THROW_ON_ERROR);

        assertCount(5, $json['data']);
    }

    public function testRequestWithApiTokenAndCountEqualsThreeParamReturnsThreeQuotes(): void
    {
        $response = $this->get('/api/quotes?count=3', ['x-api-key' => env('APP_API_KEY')]);
        $json = json_decode($response->getContent(), true, flags: JSON_THROW_ON_ERROR);

        assertCount(3, $json['data']);
    }

    public function testRequestWithApiTokenAndCountEqualsZeroParamReturnsError(): void
    {
        $response = $this->get('/api/quotes?count=0', ['x-api-key' => env('APP_API_KEY'), 'Accept' => 'application/json']);
        $response->assertJsonFragment(['errors' => ['count' => ['The count field must be at least 1.']]]);
    }

    public function testTwoConsecutiveRequestsReturnSameQuotes(): void
    {
        $response1 = $this->get('/api/quotes', ['x-api-key' => env('APP_API_KEY')]);
        $response2 = $this->get('/api/quotes', ['x-api-key' => env('APP_API_KEY')]);

        assertEquals($response1->json(), $response2->json());
    }
}
