<?php

namespace App\Http\Controllers;

use App\Interfaces\AuthInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AuthController extends Controller
{
    public AuthInterface $authService;
    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request): JsonResponse
    {
        return $this->authService->registerUser($request);
    }

    public function login(Request $request): JsonResponse
    {
        return $this->authService->loginUser($request);
    }

    public function logout(): JsonResponse
    {
        return $this->authService->logoutUser();
    }

    public function getUser(): JsonResponse
    {
        return $this->authService->getUser();
    }

    public function showRegisterForm(): BinaryFileResponse
    {
        return response()->file(public_path('register.html'));
    }

    public function showLoginForm(): BinaryFileResponse
    {
        return response()->file(public_path('login.html'));
    }
}
