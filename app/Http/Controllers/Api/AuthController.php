<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller {
    /**
     * AuthService instance.
     *
     * @var AuthService
     */
    public AuthService $authService;

    /**
     * Create a new AuthController instance.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    /**
     * Authenticate a user and return an access token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse {
        try {
            return $this->authService->login($request->email, $request->password);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to login',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Logout the authenticated user.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse {
        try {
            $user = $request->user('sanctum');
            
            if(!$user) {
                return response()->json([
                    'message' => 'No authenticated user',
                    'errors' => ['User is not logged in'],
                ], JsonResponse::HTTP_UNAUTHORIZED);
            }
            
            return $this->authService->logout($user);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to logout',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}