<?php

namespace App\Http\Controllers;

use App\Services\QuoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function __construct(
        private readonly QuoteService $quoteService
    )
    {}

    public function quotes(Request $request): JsonResponse
    {
        $params = $request->validate([
            'count' => 'integer|min:1',
        ]);

        if (!array_key_exists('count', $params)) {
            $params['count'] = 5;
        }

        return response()->json(
            $this->quoteService->getQuotes($params['count'])->pluck('quote')
        );
    }

    public function refresh(Request $request): JsonResponse
    {
        $params = $request->validate([
            'count' => 'integer|min:1',
        ]);

        if (!array_key_exists('count', $params)) {
            $params['count'] = 5;
        }

        return response()->json(
            $this->quoteService->refresh($params['count'])->pluck('quote')
        );
    }
}
