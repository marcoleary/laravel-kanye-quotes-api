<?php

namespace App\Repositories;

use App\Models\Quote;
use Illuminate\Support\Collection;

interface QuoteRepositoryInterface
{
    /**
     * @return Collection<int, Quote>
     */
    public function getQuotes(): Collection;
}
