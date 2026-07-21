<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\SkillCategory */
class SkillCategoryResource extends JsonResource
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
            'order' => $this->order,
            'is_active' => $this->is_active,
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
