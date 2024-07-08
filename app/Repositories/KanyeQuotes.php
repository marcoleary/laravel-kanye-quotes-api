<?php

namespace App\Repositories;

use App\Http\Exceptions\KanyeException;
use App\Models\Quote;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;

class KanyeQuotes implements QuoteRepositoryInterface
{
    public function __construct(
        private Client $client
    )
    {
    }

    /**
     * @return Collection<int, Quote>
     * @throws KanyeException
     * @throws \JsonException
     * @throws GuzzleException
     */
    public function getQuotes(): Collection
    {
        $response = $this->client->request('GET', 'quotes');

        if ($response->getStatusCode() !== 200) {
            throw new KanyeException();
        }

        /** @var array<int, string> $decodedResponse */
        $decodedResponse = json_decode(
            (string) $response->getBody(),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        /** @var Collection<int, string> $responseCollection */
        $responseCollection = collect($decodedResponse);

        /** @var Collection<int, Quote> $result */
        $result = $responseCollection->map(fn($item) => new Quote(['quote' => $item]));

        return $result;
    }
}
