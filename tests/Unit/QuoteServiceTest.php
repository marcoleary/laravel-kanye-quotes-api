<?php

namespace Tests\Unit;

use App\Models\Quote;
use App\Repositories\QuoteRepositoryInterface;
use App\Services\QuoteService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Mockery;
use PHPUnit\Framework\TestCase;

class QuoteServiceTest extends TestCase
{
    protected $quoteRepositoryMock;
    protected $quoteService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->quoteRepositoryMock = $this->createMock(QuoteRepositoryInterface::class);
        $this->quoteService = new QuoteService($this->quoteRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetQuotesFromCache(): void
    {
        $cachedQuotes = [
            ['quote' => 'Cached Quote 1'],
            ['quote' => 'Cached Quote 2'],
            ['quote' => 'Cached Quote 3'],
            ['quote' => 'Cached Quote 4'],
            ['quote' => 'Cached Quote 5'],
        ];

        Cache::shouldReceive('has')
            ->once()
            ->with('quotes')
            ->andReturn(true);

        Cache::shouldReceive('get')
            ->once()
            ->with('quotes')
            ->andReturn(base64_encode(json_encode($cachedQuotes)));

        $quotes = $this->quoteService->getQuotes();

        $this->assertInstanceOf(Collection::class, $quotes);
        $this->assertCount(5, $quotes);
        $this->assertContainsOnlyInstancesOf(Quote::class, $quotes);
    }

    public function testGetQuotesFromRepository(): void
    {
        $mockQuotes = collect([
            new Quote(['quote' => 'Repository Quote 1']),
            new Quote(['quote' => 'Repository Quote 2']),
            new Quote(['quote' => 'Repository Quote 3']),
            new Quote(['quote' => 'Repository Quote 4']),
            new Quote(['quote' => 'Repository Quote 5']),
        ]);

        Cache::shouldReceive('has')
            ->once()
            ->with('quotes')
            ->andReturn(false);

        Cache::shouldReceive('set')
            ->once()
            ->with('quotes', base64_encode(json_encode($mockQuotes->pluck('quote')->toArray())));

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getQuotes')
            ->willReturn($mockQuotes);

        $quotes = $this->quoteService->getQuotes();

        $this->assertInstanceOf(Collection::class, $quotes);
        $this->assertCount(5, $quotes);
        $this->assertContainsOnlyInstancesOf(Quote::class, $quotes);
    }

    public function testRefreshQuotes(): void
    {
        Cache::shouldReceive('delete')
            ->once()
            ->with('quotes');

        $mockQuotes = collect([
            new Quote(['quote' => 'Repository Quote 1']),
            new Quote(['quote' => 'Repository Quote 2']),
            new Quote(['quote' => 'Repository Quote 3']),
            new Quote(['quote' => 'Repository Quote 4']),
            new Quote(['quote' => 'Repository Quote 5']),
        ]);

        Cache::shouldReceive('has')
            ->once()
            ->with('quotes')
            ->andReturn(false);

        Cache::shouldReceive('set')
            ->once()
            ->with('quotes', base64_encode(json_encode($mockQuotes->pluck('quote')->toArray())));

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getQuotes')
            ->willReturn($mockQuotes);

        $quotes = $this->quoteService->refresh();

        $this->assertInstanceOf(Collection::class, $quotes);
        $this->assertCount(5, $quotes);
        $this->assertContainsOnlyInstancesOf(Quote::class, $quotes);
    }
}
