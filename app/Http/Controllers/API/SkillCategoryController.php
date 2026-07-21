<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ReorderRequest;
use App\Http\Requests\API\StoreSkillCategoryRequest;
use App\Http\Requests\API\UpdateSkillCategoryRequest;
use App\Http\Resources\API\SkillCategoryResource;
use App\Http\Responses\ApiResponse;
use App\Services\SkillService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillCategoryController extends Controller
{
    public function __construct(
        private readonly SkillService $skills,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $categories = $this->skills->listCategories($request->user());

        return ApiResponse::success(
            'Categories retrieved successfully.',
            SkillCategoryResource::collection($categories)->resolve()
        );
    }

    public function store(StoreSkillCategoryRequest $request): JsonResponse
    {
        $category = $this->skills->createCategory(
            $request->user(),
            $request->validated()
        );

        $category->load(['skills' => fn ($q) => $q->orderBy('order')]);

        return ApiResponse::success(
            'Category created successfully.',
            (new SkillCategoryResource($category))->resolve(),
            201
        );
    }

    public function update(UpdateSkillCategoryRequest $request, int $id): JsonResponse
    {
        $category = $this->skills->updateCategory(
            $request->user(),
            $id,
            $request->validated()
        );

        return ApiResponse::success(
            'Category updated successfully.',
            (new SkillCategoryResource($category))->resolve()
        );
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->skills->deleteCategory($request->user(), $id);

        return ApiResponse::success('Category deleted successfully.');
    }

    public function toggle(Request $request, int $id): JsonResponse
    {
        $category = $this->skills->toggleCategory($request->user(), $id);

        return ApiResponse::success(
            'Category status updated.',
            (new SkillCategoryResource($category))->resolve()
        );
    }

    public function reorder(ReorderRequest $request): JsonResponse
    {
        $categories = $this->skills->reorderCategories(
            $request->user(),
            $request->validated('ids')
        );

        return ApiResponse::success(
            'Categories reordered successfully.',
            SkillCategoryResource::collection($categories)->resolve()
        );
    }
}
