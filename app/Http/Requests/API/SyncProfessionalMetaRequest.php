<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SyncProfessionalMetaRequest extends FormRequest
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
            'meta' => ['required', 'array', 'min:1'],
            'meta.*.key' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9_]+$/'],
            'meta.*.value' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'meta.*.key.regex' => 'Meta keys may only contain lowercase letters, numbers, and underscores.',
        ];
    }
}
