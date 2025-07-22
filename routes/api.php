<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Http\Controllers\Api\RestauranteController;


Route::middleware('apikey')->group(function () {
    Route::apiResource('restaurantes', RestauranteController::class);
});