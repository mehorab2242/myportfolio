<?php

use App\Http\Controllers\API\Admin\UserController as AdminUserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EducationController;
use App\Http\Controllers\API\ExperienceController;
use App\Http\Controllers\API\PortfolioCategoryController;
use App\Http\Controllers\API\PortfolioItemController;
use App\Http\Controllers\API\ProfessionalMetaController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\SkillCategoryController;
use App\Http\Controllers\API\SkillController;
use App\Http\Controllers\API\SocialLinkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Prefix: /api
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/settings', [SettingController::class, 'show']);

    // Profile module
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'upsert']);
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar']);
    Route::post('/profile/cover', [ProfileController::class, 'uploadCover']);

    Route::post('/social-links', [SocialLinkController::class, 'store']);
    Route::delete('/social-links/{id}', [SocialLinkController::class, 'destroy']);

    Route::post('/professional-meta', [ProfessionalMetaController::class, 'store']);

    // Skills module (categories + skills) — multi-tenant via user_id
    Route::get('/categories', [SkillCategoryController::class, 'index']);
    Route::post('/categories', [SkillCategoryController::class, 'store']);
    Route::patch('/categories/reorder', [SkillCategoryController::class, 'reorder']);
    Route::put('/categories/{id}', [SkillCategoryController::class, 'update']);
    Route::delete('/categories/{id}', [SkillCategoryController::class, 'destroy']);
    Route::patch('/categories/{id}/toggle', [SkillCategoryController::class, 'toggle']);

    Route::get('/skills', [SkillController::class, 'index']);
    Route::post('/skills', [SkillController::class, 'store']);
    Route::patch('/skills/reorder', [SkillController::class, 'reorder']);
    Route::put('/skills/{id}', [SkillController::class, 'update']);
    Route::delete('/skills/{id}', [SkillController::class, 'destroy']);
    Route::patch('/skills/{id}/toggle', [SkillController::class, 'toggle']);

    // Portfolio items (universal: projects / case studies / works)
    Route::get('/portfolio-categories', [PortfolioCategoryController::class, 'index']);
    Route::post('/portfolio-categories', [PortfolioCategoryController::class, 'store']);
    Route::patch('/portfolio-categories/reorder', [PortfolioCategoryController::class, 'reorder']);
    Route::put('/portfolio-categories/{id}', [PortfolioCategoryController::class, 'update']);
    Route::delete('/portfolio-categories/{id}', [PortfolioCategoryController::class, 'destroy']);
    Route::patch('/portfolio-categories/{id}/toggle', [PortfolioCategoryController::class, 'toggle']);

    Route::get('/portfolio-items', [PortfolioItemController::class, 'index']);
    Route::post('/portfolio-items', [PortfolioItemController::class, 'store']);
    Route::patch('/portfolio-items/reorder', [PortfolioItemController::class, 'reorder']);
    Route::put('/portfolio-items/{id}', [PortfolioItemController::class, 'update']);
    Route::delete('/portfolio-items/{id}', [PortfolioItemController::class, 'destroy']);
    Route::patch('/portfolio-items/{id}/toggle', [PortfolioItemController::class, 'toggle']);
    Route::post('/portfolio-items/{id}/media', [PortfolioItemController::class, 'uploadMedia']);
    Route::patch('/portfolio-items/{id}/media/reorder', [PortfolioItemController::class, 'reorderMedia']);
    Route::delete('/portfolio-media/{id}', [PortfolioItemController::class, 'destroyMedia']);

    // Education module — multi-tenant via user_id
    Route::get('/educations', [EducationController::class, 'index']);
    Route::post('/educations', [EducationController::class, 'store']);
    Route::patch('/educations/reorder', [EducationController::class, 'reorder']);
    Route::put('/educations/{id}', [EducationController::class, 'update']);
    Route::delete('/educations/{id}', [EducationController::class, 'destroy']);
    Route::patch('/educations/{id}/toggle', [EducationController::class, 'toggle']);

    // Experience module — multi-tenant via user_id
    Route::get('/experiences', [ExperienceController::class, 'index']);
    Route::post('/experiences', [ExperienceController::class, 'store']);
    Route::patch('/experiences/reorder', [ExperienceController::class, 'reorder']);
    Route::put('/experiences/{id}', [ExperienceController::class, 'update']);
    Route::delete('/experiences/{id}', [ExperienceController::class, 'destroy']);
    Route::patch('/experiences/{id}/toggle', [ExperienceController::class, 'toggle']);

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::get('/users/{id}', [AdminUserController::class, 'show']);
        Route::put('/users/{id}', [AdminUserController::class, 'update']);
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);

        Route::put('/settings', [SettingController::class, 'update']);
    });
});
