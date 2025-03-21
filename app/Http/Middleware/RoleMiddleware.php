<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        try {
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();

            $userRole = $payload->get('role');

            if ($userRole !== $role) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        } catch (Exception) {
            return response()->json(['error' => 'Token Invalid or Expired'], 401);
        }

        return $next($request);
    }
}
