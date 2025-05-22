<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * register a new user
     */
    public function register(RegisterRequest $request):JsonResponse
    {
        $user = $this->authService->register($request->validated());
        $token = $user->createToken('api_token')->plainTextToken;

        return $this->successResponse([
            'user'  => $user,
            'token' => $token
        ], 'User registered successfully', 201);
    }

    /**
     * user's login
     */
    public function login(LoginRequest $request):JsonResponse
    {
        $token = $this->authService->login($request->validated());

        if (! $token) {
            return $this->errorResponse('password or email is incorrect , please check again', 401);
        }

        return $this->successResponse(['token' => $token], 'Login successful');
    }

    /**
     * user's logout
     */
    public function logout(Request $request):JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->successResponse(null, 'Logged out successfully');
    }
}
