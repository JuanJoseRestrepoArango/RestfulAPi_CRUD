<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\RestauranteNoEncontradoException;
use App\Helpers\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
        'apikey' => ApiKeyMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
         $exceptions->render(function (RestauranteNoEncontradoException $e,  $request) {
            if ($request->expectsJson()) {
                return ApiResponse::error('Restaurante no encontrado', 404);
            }
            return response()->view('errors.restaurante-no-encontrado', [], 404);
        });

        $exceptions->render(function (ValidationException $e,  $request) {
            if ($request->expectsJson()) {
                 return ApiResponse::error('Datos inválidos', 422);
            }
            return null; 
        });

        $exceptions->render(function (QueryException $e,  $request) {
            if ($request->expectsJson()) {
                return ApiResponse::error('Error de base de datos', 500);
            }
            return null;
        });

        
        $exceptions->render(function (Throwable $e,  $request) {
            if ($request->expectsJson()) {
                return ApiResponse::error('Error inesperado: ' . $e->getMessage(), 500);
            }
            return null;
        });
    })->create();
