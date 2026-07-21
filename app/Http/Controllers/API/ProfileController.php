<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UploadProfileImageRequest;
use App\Http\Requests\API\UpsertProfileRequest;
use App\Http\Resources\API\ProfileBundleResource;
use App\Http\Resources\API\ProfileResource;
use App\Http\Responses\ApiResponse;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profiles,
    ) {}

    /**
     * Return the authenticated user's full profile bundle.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $this->profiles->loadBundle($request->user());

        return ApiResponse::success(
            'Profile retrieved successfully.',
            (new ProfileBundleResource($user))->resolve()
        );
    }

    /**
     * Create or update the authenticated user's profile.
     */
    public function upsert(UpsertProfileRequest $request): JsonResponse
    {
        $profile = $this->profiles->upsertProfile(
            $request->user(),
            $request->validated()
        );

        $user = $this->profiles->loadBundle($request->user());

        return ApiResponse::success(
            'Profile saved successfully.',
            (new ProfileBundleResource($user))->resolve()
        );
    }

    /**
     * Upload and set the profile avatar.
     */
    public function uploadAvatar(UploadProfileImageRequest $request): JsonResponse
    {
        $profile = $this->profiles->storeAvatar(
            $request->user(),
            $request->file('image')
        );

        return ApiResponse::success('Avatar uploaded successfully.', [
            'profile' => (new ProfileResource($profile))->resolve(),
        ]);
    }

    /**
     * Upload and set the profile cover image.
     */
    public function uploadCover(UploadProfileImageRequest $request): JsonResponse
    {
        $profile = $this->profiles->storeCover(
            $request->user(),
            $request->file('image')
        );

        return ApiResponse::success('Cover image uploaded successfully.', [
            'profile' => (new ProfileResource($profile))->resolve(),
        ]);
    }
}
