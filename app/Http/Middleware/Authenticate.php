<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->bearerToken()) {
            return route('login');
        }

        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return route('login');
        }

        return $request->expectsJson() ? null : route('login');
    }
}
