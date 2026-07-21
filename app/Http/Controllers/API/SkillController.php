<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ReorderRequest;
use App\Http\Requests\API\StoreSkillRequest;
use App\Http\Requests\API\UpdateSkillRequest;
use App\Http\Resources\API\SkillResource;
use App\Http\Responses\ApiResponse;
use App\Services\SkillService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function __construct(
        private readonly SkillService $skills,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $categoryId = $request->integer('category_id') ?: null;

        $items = $this->skills->listSkills($request->user(), $categoryId);

        return ApiResponse::success(
            'Skills retrieved successfully.',
            SkillResource::collection($items)->resolve()
        );
    }

    public function store(StoreSkillRequest $request): JsonResponse
    {
        $skill = $this->skills->createSkill(
            $request->user(),
            $request->validated()
        );

        return ApiResponse::success(
            'Skill created successfully.',
            (new SkillResource($skill))->resolve(),
            201
        );
    }

    public function update(UpdateSkillRequest $request, int $id): JsonResponse
    {
        $skill = $this->skills->updateSkill(
            $request->user(),
            $id,
            $request->validated()
        );

        return ApiResponse::success(
            'Skill updated successfully.',
            (new SkillResource($skill))->resolve()
        );
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->skills->deleteSkill($request->user(), $id);

        return ApiResponse::success('Skill deleted successfully.');
    }

    public function toggle(Request $request, int $id): JsonResponse
    {
        $skill = $this->skills->toggleSkill($request->user(), $id);

        return ApiResponse::success(
            'Skill status updated.',
            (new SkillResource($skill))->resolve()
        );
    }

    public function reorder(ReorderRequest $request): JsonResponse
    {
        $categoryId = $request->validated('category_id');

        $items = $this->skills->reorderSkills(
            $request->user(),
            $request->validated('ids'),
            $categoryId !== null ? (int) $categoryId : null
        );

        return ApiResponse::success(
            'Skills reordered successfully.',
            SkillResource::collection($items)->resolve()
        );
    }
}
