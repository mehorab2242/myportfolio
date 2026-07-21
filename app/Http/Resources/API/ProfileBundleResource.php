<?php

namespace App\Http\Resources\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Full portfolio profile payload for the authenticated user.
 *
 * @mixin User
 */
class ProfileBundleResource extends JsonResource
{
    /**
     * @return array{profile: mixed, social_links: mixed, meta: mixed}
     */
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'profile' => $user->profile
                ? (new ProfileResource($user->profile))->resolve()
                : null,
            'social_links' => SocialLinkResource::collection(
                $user->socialLinks()->orderBy('platform')->get()
            )->resolve(),
            'meta' => ProfessionalMetaResource::collection(
                $user->professionalMeta()->orderBy('key')->get()
            )->resolve(),
        ];
    }
}
