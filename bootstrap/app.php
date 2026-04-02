<?php

use App\Http\Middleware\ApiAuth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        // Handle authentication errors
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated',
                    'errors' => ['Authentication required'],
                ], JsonResponse::HTTP_UNAUTHORIZED);
            }
        });
        
        // Handle method not allowed errors (POST on GET route)
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Method not allowed',
                    'errors' => ['The ' . $request->method() . ' method is not supported for this endpoint.'],
                ], JsonResponse::HTTP_METHOD_NOT_ALLOWED);
            }
        });
        
        // Handle not found errors
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Not found',
                    'errors' => ['The requested resource was not found.'],
                ], JsonResponse::HTTP_NOT_FOUND);
            }
        });
        
        // Handle any other exceptions
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Server error',
                    'errors' => [$e->getMessage()],
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    })->create();
