<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user('sanctum')) {
            return response()->json([
                'message' => 'Unauthenticated',
                'errors' => ['Authentication required'],
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
