<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ReorderRequest;
use App\Http\Requests\API\StorePortfolioItemRequest;
use App\Http\Requests\API\UpdatePortfolioItemRequest;
use App\Http\Requests\API\UploadPortfolioMediaRequest;
use App\Http\Resources\API\PortfolioItemResource;
use App\Http\Resources\API\PortfolioMediaResource;
use App\Http\Responses\ApiResponse;
use App\Services\PortfolioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioItemController extends Controller
{
    public function __construct(
        private readonly PortfolioService $portfolio,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $categoryId = $request->filled('category_id')
            ? $request->integer('category_id')
            : null;

        $items = $this->portfolio->listItems($request->user(), $categoryId);

        return ApiResponse::success(
            'Portfolio items retrieved successfully.',
            PortfolioItemResource::collection($items)->resolve()
        );
    }

    public function store(StorePortfolioItemRequest $request): JsonResponse
    {
        $item = $this->portfolio->createItem(
            $request->user(),
            $request->validated()
        );

        return ApiResponse::success(
            'Portfolio item created successfully.',
            (new PortfolioItemResource($item))->resolve(),
            201
        );
    }

    public function update(UpdatePortfolioItemRequest $request, int $id): JsonResponse
    {
        $item = $this->portfolio->updateItem(
            $request->user(),
            $id,
            $request->validated()
        );

        return ApiResponse::success(
            'Portfolio item updated successfully.',
            (new PortfolioItemResource($item))->resolve()
        );
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->portfolio->deleteItem($request->user(), $id);

        return ApiResponse::success('Portfolio item deleted successfully.');
    }

    public function toggle(Request $request, int $id): JsonResponse
    {
        $item = $this->portfolio->toggleItem($request->user(), $id);

        return ApiResponse::success(
            'Portfolio item status updated.',
            (new PortfolioItemResource($item))->resolve()
        );
    }

    public function reorder(ReorderRequest $request): JsonResponse
    {
        $items = $this->portfolio->reorderItems(
            $request->user(),
            $request->validated('ids')
        );

        return ApiResponse::success(
            'Portfolio items reordered successfully.',
            PortfolioItemResource::collection($items)->resolve()
        );
    }

    public function uploadMedia(UploadPortfolioMediaRequest $request, int $id): JsonResponse
    {
        $media = $this->portfolio->storeMedia(
            $request->user(),
            $id,
            $request->file('images', [])
        );

        return ApiResponse::success(
            'Media uploaded successfully.',
            PortfolioMediaResource::collection($media)->resolve(),
            201
        );
    }

    public function reorderMedia(ReorderRequest $request, int $id): JsonResponse
    {
        $media = $this->portfolio->reorderMedia(
            $request->user(),
            $id,
            $request->validated('ids')
        );

        return ApiResponse::success(
            'Media reordered successfully.',
            PortfolioMediaResource::collection($media)->resolve()
        );
    }

    public function destroyMedia(Request $request, int $id): JsonResponse
    {
        $this->portfolio->deleteMedia($request->user(), $id);

        return ApiResponse::success('Media deleted successfully.');
    }
}
