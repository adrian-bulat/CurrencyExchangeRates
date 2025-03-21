<?php

namespace App\Services;

use App\Interfaces\AuthInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthInterface
{
    public function registerUser(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|alpha|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), Response::HTTP_BAD_REQUEST);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);
        $message = 'Registered successfully';

        return response()->json(compact('token', 'message'), Response::HTTP_CREATED);
    }

    public function loginUser(Request $request): JsonResponse
    {
        $message = 'Logged in successfully';
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            info(response()->json($validator->errors()->toJson(), Response::HTTP_BAD_REQUEST));
            return response()->json($validator->errors()->toJson(), Response::HTTP_BAD_REQUEST);
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                info(response()->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED));
                return response()->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
            }

            $user = auth()->user();

//            TODO: (optional - add role to JWT)
//            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);
            info(response()->json(compact('user', 'token', 'message'), Response::HTTP_OK));
            return response()->json(compact('user', 'token', 'message'), Response::HTTP_OK);
        } catch (JWTException) {
            info(response()->json(['error' => 'Could not create token'], Response::HTTP_INTERNAL_SERVER_ERROR));
            return response()->json(['error' => 'Could not create token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logoutUser(): JsonResponse
    {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
        return response()->json(['message' => 'Logged out successfully'], Response::HTTP_OK);
    }

    public function getUser(): JsonResponse
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } catch (JWTException) {
            return response()->json(['error' => 'Invalid token'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(compact('user'));
    }
}
