<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\PublicPortfolioResource;
use App\Http\Responses\ApiResponse;
use App\Services\PublicPortfolioService;
use Illuminate\Http\JsonResponse;

class PublicPortfolioController extends Controller
{
    public function __construct(
        private readonly PublicPortfolioService $portfolios,
    ) {}

    /**
     * Public portfolio by username — only that user's active data.
     */
    public function show(string $username): JsonResponse
    {
        $user = $this->portfolios->resolveByUsername($username);

        return ApiResponse::success(
            'Portfolio retrieved successfully.',
            (new PublicPortfolioResource($user))->resolve()
        );
    }
}
