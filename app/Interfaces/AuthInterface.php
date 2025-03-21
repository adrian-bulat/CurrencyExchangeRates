<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface AuthInterface
{
    public function registerUser(Request $request): JsonResponse;
    public function loginUser(Request $request): JsonResponse;
    public function logoutUser(): JsonResponse;
    public function getUser(): JsonResponse;
}
