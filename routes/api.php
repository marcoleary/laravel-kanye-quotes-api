<?php

use App\Http\Controllers\QuoteController;
use App\Http\Middleware\JsonApiOutput;
use App\Http\Middleware\RequiresApiToken;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => response()->json(['status' => true]));

Route::middleware([RequiresApiToken::class, JsonApiOutput::class])->group(function () {
    Route::get('/quotes', [QuoteController::class, 'quotes']);
    Route::get('/refresh', [QuoteController::class, 'refresh']);
});
