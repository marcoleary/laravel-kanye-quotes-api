<?php

namespace App\Services;

use App\Models\Quote;
use App\Repositories\QuoteRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

readonly class QuoteService
{
    public function __construct(
        private QuoteRepositoryInterface $quoteRepository
    ) {}

    /**
     * @return Collection<int, Quote>
     * @throws \JsonException
     * @throws \Exception
     */
    public function getQuotes(int $count = 5): Collection
    {
        if (Cache::has('quotes')) {
            $cache = Cache::get('quotes');

            if (!is_string($cache)) {
                throw new \Exception();
            }

            /** @var array<int, string> $decodedResponse */
            $decodedResponse = json_decode(
                base64_decode($cache),
                true,
                flags: JSON_THROW_ON_ERROR
            );

            /** @var Collection<int, Quote> $result */
            $result = collect($decodedResponse)
                ->map(fn ($item) => new Quote(['quote' => $item]))
                ->take($count);
        } else {
            /** @var Collection<int, Quote> $result */
            $result = $this->quoteRepository->getQuotes()->random($count);

            Cache::set('quotes', base64_encode($result->pluck('quote')->toJson() ?: ''));
        }

        return $result;
    }

    /**
     * @return Collection<int, Quote>
     * @throws \JsonException
     * @throws \Exception
     */
    public function refresh(int $count = 5): Collection
    {
        Cache::delete('quotes');

        return $this->getQuotes($count);
    }
}
