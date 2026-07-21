<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Profile */
class ProfileResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'title' => $this->title,
            'bio' => $this->bio,
            'about' => $this->about,
            'avatar' => $this->avatar,
            'avatar_url' => $this->avatarUrl(),
            'cover_image' => $this->cover_image,
            'cover_image_url' => $this->coverImageUrl(),
            'location' => $this->location,
            'email_public' => $this->email_public,
            'phone' => $this->phone,
            'phone_public' => $this->phone_public,
            'website' => $this->website,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
