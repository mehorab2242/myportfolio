<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UpdateSettingRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Get admin panel brand colours (authenticated).
     */
    public function show(): JsonResponse
    {
        $settings = Setting::current();

        return ApiResponse::success('Settings retrieved successfully.', $settings->adminThemePayload());
    }

    /**
     * Update admin panel brand colours (admin only).
     */
    public function update(UpdateSettingRequest $request): JsonResponse
    {
        $settings = Setting::current();
        $validated = $request->validated();

        if (array_key_exists('admin_primary', $validated) && $validated['admin_primary']) {
            $settings->admin_primary = strtolower($validated['admin_primary']);
        }

        if (array_key_exists('admin_secondary', $validated) && $validated['admin_secondary']) {
            $settings->admin_secondary = strtolower($validated['admin_secondary']);
        }

        $settings->save();

        return ApiResponse::success('Admin brand colours updated successfully.', $settings->fresh()->adminThemePayload());
    }
}
