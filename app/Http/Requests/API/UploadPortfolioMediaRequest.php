<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UploadPortfolioMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1', 'max:12'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'images.required' => 'At least one image is required.',
            'images.*.image' => 'Each file must be an image.',
        ];
    }
}
