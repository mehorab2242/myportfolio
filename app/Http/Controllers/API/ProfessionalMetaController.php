<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SyncProfessionalMetaRequest;
use App\Http\Resources\API\ProfessionalMetaResource;
use App\Http\Responses\ApiResponse;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;

class ProfessionalMetaController extends Controller
{
    public function __construct(
        private readonly ProfileService $profiles,
    ) {}

    /**
     * Save dynamic profession-specific key/value fields.
     */
    public function store(SyncProfessionalMetaRequest $request): JsonResponse
    {
        $meta = $this->profiles->syncProfessionalMeta(
            $request->user(),
            $request->validated('meta')
        );

        return ApiResponse::success('Professional meta saved successfully.', [
            'meta' => ProfessionalMetaResource::collection($meta)->resolve(),
        ]);
    }
}
