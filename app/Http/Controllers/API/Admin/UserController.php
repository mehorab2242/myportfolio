<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * List all users.
     */
    public function index(): JsonResponse
    {
        $users = User::query()
            ->orderBy('name')
            ->get();

        return ApiResponse::success('Users retrieved successfully.', [
            'users' => $users,
        ]);
    }

    /**
     * Show a single user.
     */
    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::error('User not found.', null, 404);
        }

        return ApiResponse::success('User retrieved successfully.', [
            'user' => $user,
        ]);
    }

    /**
     * Update a user.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::error('User not found.', null, 404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'username' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^\S+$/', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => ['sometimes', 'nullable', 'string', 'min:6'],
            'role' => ['sometimes', 'required', Rule::in([User::ROLE_ADMIN, User::ROLE_USER])],
            'status' => ['sometimes', 'required', 'boolean'],
        ]);

        if (array_key_exists('password', $validated)) {
            if ($validated['password']) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
        }

        $user->update($validated);

        return ApiResponse::success('User updated successfully.', [
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Delete a user.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::error('User not found.', null, 404);
        }

        if ($user->id === request()->user()->id) {
            return ApiResponse::error('You cannot delete your own account.', null, 403);
        }

        $user->delete();

        return ApiResponse::success('User deleted successfully.');
    }
}
