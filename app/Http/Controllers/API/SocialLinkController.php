<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SyncSocialLinksRequest;
use App\Http\Resources\API\SocialLinkResource;
use App\Http\Responses\ApiResponse;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function __construct(
        private readonly ProfileService $profiles,
    ) {}

    /**
     * Add or update social links (array-based upsert).
     */
    public function store(SyncSocialLinksRequest $request): JsonResponse
    {
        $links = $this->profiles->syncSocialLinks(
            $request->user(),
            $request->validated('links')
        );

        return ApiResponse::success('Social links saved successfully.', [
            'social_links' => SocialLinkResource::collection($links)->resolve(),
        ]);
    }

    /**
     * Delete a social link owned by the authenticated user.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->profiles->deleteSocialLink($request->user(), $id);

        return ApiResponse::success('Social link deleted successfully.');
    }
}
