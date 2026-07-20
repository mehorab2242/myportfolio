<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user and return a Sanctum token.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $this->generateUsername($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_USER,
            'status' => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success('Registration successful.', [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Authenticate a user and return a Sanctum token.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->status) {
            return ApiResponse::error('Your account is inactive.', null, 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success('Login successful.', [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Revoke the current access token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success('Logged out successfully.');
    }

    /**
     * Return the authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success('Authenticated user retrieved.', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Generate a unique username from an email address.
     */
    private function generateUsername(string $email): string
    {
        $base = Str::slug(Str::before($email, '@'), '_');
        $username = $base !== '' ? $base : 'user';
        $candidate = $username;
        $suffix = 1;

        while (User::where('username', $candidate)->exists()) {
            $candidate = $username.'_'.$suffix;
            $suffix++;
        }

        return $candidate;
    }
}
