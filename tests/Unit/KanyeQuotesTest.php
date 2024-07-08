<?php

namespace Tests\Unit;

use App\Http\Exceptions\KanyeException;
use App\Repositories\KanyeQuotes;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class KanyeQuotesTest extends MockeryTestCase
{
    protected $clientMock;
    protected $kanyeQuotes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientMock = Mockery::mock(Client::class);
        $this->kanyeQuotes = new KanyeQuotes($this->clientMock);
    }

    public function testGetQuotesSuccess()
    {
        $apiResponse = new Response(200, [], json_encode(['Quote 1', 'Quote 2', 'Quote 3']));

        $this->clientMock->shouldReceive('request')
            ->once()
            ->with('GET', 'quotes')
            ->andReturn($apiResponse);

        $result = $this->kanyeQuotes->getQuotes();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
        $this->assertEquals('Quote 1', $result[0]->quote);
        $this->assertEquals('Quote 2', $result[1]->quote);
        $this->assertEquals('Quote 3', $result[2]->quote);
    }

    public function testGetQuotesFailure()
    {
        $apiResponse = new Response(500);

        $this->clientMock->shouldReceive('request')
            ->once()
            ->with('GET', 'quotes')
            ->andReturn($apiResponse);

        $this->expectException(KanyeException::class);

        $this->kanyeQuotes->getQuotes();
    }
}
