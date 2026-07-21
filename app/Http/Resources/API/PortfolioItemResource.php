<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PortfolioItem */
class PortfolioItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $media = $this->whenLoaded('media');

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'category_id' => $this->category_id,
            'category' => $this->when(
                $this->relationLoaded('category'),
                fn () => $this->category
                    ? (new PortfolioCategoryResource($this->category))->resolve()
                    : null
            ),
            'client_name' => $this->client_name,
            'project_url' => $this->project_url,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'order' => $this->order,
            'thumbnail_url' => $this->when(
                $this->relationLoaded('media'),
                fn () => $this->media->first()?->url()
            ),
            'media' => PortfolioMediaResource::collection($media),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
