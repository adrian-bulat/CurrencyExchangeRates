<?php

namespace App\Http\Controllers;

use App\Interfaces\AuthInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthInterface $authService
    ) {
    }

    public function register(Request $request): JsonResponse
    {
        return $this->authService->register($request);
    }

    public function login(Request $request): JsonResponse
    {
        return $this->authService->login($request);
    }

    public function logout(): JsonResponse
    {
        return $this->authService->logout();
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
