<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // REGISTER
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        $user->sendEmailVerificationNotification();

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('api-token')->plainTextToken,
            'message' => 'Please check your email to verify your account',
        ], 201);
    }

    // LOGIN
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = $request->user();

        // Uncomment for email verification requirement
        /*
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Please verify your email address first.',
            ], 403);
        }
        */

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    // LOGOUT
    public function logout(Request $request): JsonResponse
    {
        // More efficient token revocation
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    // GET CURRENT USER
    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}