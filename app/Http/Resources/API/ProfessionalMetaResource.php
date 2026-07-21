<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ProfessionalMeta */
class ProfessionalMetaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'key' => $this->key,
            'value' => $this->value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
