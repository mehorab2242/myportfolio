<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
            'admin_primary' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'admin_secondary' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'admin_primary.regex' => 'Enter a valid hex colour, e.g. #0d9488.',
            'admin_secondary.regex' => 'Enter a valid hex colour, e.g. #0d9488.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $payload = [];

        foreach (['admin_primary', 'admin_secondary'] as $field) {
            if (! $this->has($field) || $this->input($field) === null) {
                continue;
            }

            $value = strtolower(trim((string) $this->input($field)));

            if ($value !== '' && ! str_starts_with($value, '#')) {
                $value = '#'.$value;
            }

            $payload[$field] = $value;
        }

        if ($payload !== []) {
            $this->merge($payload);
        }
    }
}
