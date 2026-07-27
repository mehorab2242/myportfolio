<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ReorderRequest;
use App\Http\Requests\API\StoreExperienceRequest;
use App\Http\Requests\API\UpdateExperienceRequest;
use App\Http\Resources\API\ExperienceResource;
use App\Http\Responses\ApiResponse;
use App\Services\ExperienceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function __construct(
        private readonly ExperienceService $experiences,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $items = $this->experiences->list($request->user());

        return ApiResponse::success(
            'Experiences retrieved successfully.',
            ExperienceResource::collection($items)->resolve()
        );
    }

    public function store(StoreExperienceRequest $request): JsonResponse
    {
        $experience = $this->experiences->create(
            $request->user(),
            $request->validated()
        );

        return ApiResponse::success(
            'Experience created successfully.',
            (new ExperienceResource($experience))->resolve(),
            201
        );
    }

    public function update(UpdateExperienceRequest $request, int $id): JsonResponse
    {
        $experience = $this->experiences->update(
            $request->user(),
            $id,
            $request->validated()
        );

        return ApiResponse::success(
            'Experience updated successfully.',
            (new ExperienceResource($experience))->resolve()
        );
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->experiences->delete($request->user(), $id);

        return ApiResponse::success('Experience deleted successfully.');
    }

    public function toggle(Request $request, int $id): JsonResponse
    {
        $experience = $this->experiences->toggle($request->user(), $id);

        return ApiResponse::success(
            'Experience status updated.',
            (new ExperienceResource($experience))->resolve()
        );
    }

    public function reorder(ReorderRequest $request): JsonResponse
    {
        $items = $this->experiences->reorder(
            $request->user(),
            $request->validated('ids')
        );

        return ApiResponse::success(
            'Experiences reordered successfully.',
            ExperienceResource::collection($items)->resolve()
        );
    }
}
