<?php

namespace App\Http\Services;

use Illuminate\Http\JsonResponse;
use App\Http\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService {
    /**
     * UserRepository instance.
     *
     * @var UserRepository
     */
    public UserRepository $userRepository;

    /**
     * Create a new AuthService instance.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }
    
    /**
     * Authenticate a user and return an access token.
     *
     * @param string $email
     * @param string $password
     * @return JsonResponse
     */
    public function login(string $email, string $password): JsonResponse {
        $user = $this->userRepository->findByEmail($email);
        
        if(!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'errors' => ['The provided credentials are incorrect.'],
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        
        $token = $user->createToken('pharmacovigilance-token')->plainTextToken;
        
        return response()->json([
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'Login successful',
        ], JsonResponse::HTTP_OK);
    }
    
    /**
     * Logout the authenticated user.
     *
     * @param \App\Models\User|null $user
     * @return JsonResponse
     */
    public function logout($user): JsonResponse {
        try {
            if(!$user) {
                return response()->json([
                    'message' => 'No authenticated user',
                    'errors' => ['User is not logged in'],
                ], JsonResponse::HTTP_UNAUTHORIZED);
            }
            
            $currentToken = $user->currentAccessToken();
            
            if($currentToken) {
                $user->tokens()->where('id', $currentToken->id)->delete();
            }
            
            return response()->json([
                'message' => 'Logout successful',
            ], JsonResponse::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Error during logout',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}