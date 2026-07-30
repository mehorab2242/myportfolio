<?php

namespace App\Http\Resources\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Public portfolio payload for /{username}.
 *
 * @mixin User
 */
class PublicPortfolioResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        $profile = $user->profile;

        return [
            'user' => [
                'name' => $user->name,
                'username' => $user->username,
            ],
            'profile' => $profile
                ? [
                    'name' => $profile->name ?: $user->name,
                    'title' => $profile->title,
                    'bio' => $profile->bio,
                    'about' => $profile->about,
                    'avatar_url' => $profile->avatarUrl(),
                    'cover_image_url' => $profile->coverImageUrl(),
                    'location' => $profile->location,
                    'email' => $profile->email_public ? $user->email : null,
                    'phone' => $profile->phone_public ? $profile->phone : null,
                    'website' => $profile->website,
                ]
                : [
                    'name' => $user->name,
                    'title' => null,
                    'bio' => null,
                    'about' => null,
                    'avatar_url' => null,
                    'cover_image_url' => null,
                    'location' => null,
                    'email' => null,
                    'phone' => null,
                    'website' => null,
                ],
            'social_links' => SocialLinkResource::collection($user->socialLinks)->resolve(),
            'meta' => ProfessionalMetaResource::collection($user->professionalMeta)->resolve(),
            'skill_categories' => SkillCategoryResource::collection($user->skillCategories)->resolve(),
            'portfolio_items' => PortfolioItemResource::collection($user->portfolioItems)->resolve(),
            'experiences' => ExperienceResource::collection($user->experiences)->resolve(),
            'educations' => EducationResource::collection($user->educations)->resolve(),
        ];
    }
}
