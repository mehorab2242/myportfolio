<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ReorderRequest;
use App\Http\Requests\API\StorePortfolioCategoryRequest;
use App\Http\Requests\API\UpdatePortfolioCategoryRequest;
use App\Http\Resources\API\PortfolioCategoryResource;
use App\Http\Responses\ApiResponse;
use App\Services\PortfolioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioCategoryController extends Controller
{
    public function __construct(
        private readonly PortfolioService $portfolio,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $categories = $this->portfolio->listCategories($request->user());

        return ApiResponse::success(
            'Portfolio categories retrieved successfully.',
            PortfolioCategoryResource::collection($categories)->resolve()
        );
    }

    public function store(StorePortfolioCategoryRequest $request): JsonResponse
    {
        $category = $this->portfolio->createCategory(
            $request->user(),
            $request->validated()
        );

        return ApiResponse::success(
            'Category created successfully.',
            (new PortfolioCategoryResource($category))->resolve(),
            201
        );
    }

    public function update(UpdatePortfolioCategoryRequest $request, int $id): JsonResponse
    {
        $category = $this->portfolio->updateCategory(
            $request->user(),
            $id,
            $request->validated()
        );

        return ApiResponse::success(
            'Category updated successfully.',
            (new PortfolioCategoryResource($category))->resolve()
        );
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->portfolio->deleteCategory($request->user(), $id);

        return ApiResponse::success('Category deleted successfully.');
    }

    public function toggle(Request $request, int $id): JsonResponse
    {
        $category = $this->portfolio->toggleCategory($request->user(), $id);

        return ApiResponse::success(
            'Category status updated.',
            (new PortfolioCategoryResource($category))->resolve()
        );
    }

    public function reorder(ReorderRequest $request): JsonResponse
    {
        $categories = $this->portfolio->reorderCategories(
            $request->user(),
            $request->validated('ids')
        );

        return ApiResponse::success(
            'Categories reordered successfully.',
            PortfolioCategoryResource::collection($categories)->resolve()
        );
    }
}
