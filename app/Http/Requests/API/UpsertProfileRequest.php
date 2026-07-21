<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UpsertProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'about' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'email_public' => ['nullable', 'boolean'],
            'phone' => ['nullable', 'string', 'max:50'],
            'phone_public' => ['nullable', 'boolean'],
            'website' => ['nullable', 'url', 'max:255'],
        ];
    }
}
