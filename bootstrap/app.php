<?php

use App\Http\Middleware\ApiAuth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'api.auth' => ApiAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Manejar errores de autenticación
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated',
                    'errors' => ['Authentication required'],
                ], 401);
            }
        });
        
        // Manejar errores de método no permitido (POST en ruta GET)
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Method not allowed',
                    'errors' => ['The ' . $request->method() . ' method is not supported for this endpoint.'],
                ], 405);
            }
        });
        
        // Manejar errores de ruta no encontrada
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Not found',
                    'errors' => ['The requested resource was not found.'],
                ], 404);
            }
        });
        
        // Manejar cualquier otra excepción
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Server error',
                    'errors' => [$e->getMessage()],
                ], 500);
            }
        });
    })->create();
