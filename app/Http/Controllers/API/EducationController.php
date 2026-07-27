<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ReorderRequest;
use App\Http\Requests\API\StoreEducationRequest;
use App\Http\Requests\API\UpdateEducationRequest;
use App\Http\Resources\API\EducationResource;
use App\Http\Responses\ApiResponse;
use App\Services\EducationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function __construct(
        private readonly EducationService $educations,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $items = $this->educations->list($request->user());

        return ApiResponse::success(
            'Educations retrieved successfully.',
            EducationResource::collection($items)->resolve()
        );
    }

    public function store(StoreEducationRequest $request): JsonResponse
    {
        $education = $this->educations->create(
            $request->user(),
            $request->validated()
        );

        return ApiResponse::success(
            'Education created successfully.',
            (new EducationResource($education))->resolve(),
            201
        );
    }

    public function update(UpdateEducationRequest $request, int $id): JsonResponse
    {
        $education = $this->educations->update(
            $request->user(),
            $id,
            $request->validated()
        );

        return ApiResponse::success(
            'Education updated successfully.',
            (new EducationResource($education))->resolve()
        );
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->educations->delete($request->user(), $id);

        return ApiResponse::success('Education deleted successfully.');
    }

    public function toggle(Request $request, int $id): JsonResponse
    {
        $education = $this->educations->toggle($request->user(), $id);

        return ApiResponse::success(
            'Education status updated.',
            (new EducationResource($education))->resolve()
        );
    }

    public function reorder(ReorderRequest $request): JsonResponse
    {
        $items = $this->educations->reorder(
            $request->user(),
            $request->validated('ids')
        );

        return ApiResponse::success(
            'Educations reordered successfully.',
            EducationResource::collection($items)->resolve()
        );
    }
}
