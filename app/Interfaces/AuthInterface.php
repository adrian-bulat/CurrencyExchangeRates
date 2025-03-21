<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface AuthInterface
{
    public function register(Request $request): JsonResponse;
    public function login(Request $request): JsonResponse;
    public function logout(): JsonResponse;
    public function getUser(): JsonResponse;
}
