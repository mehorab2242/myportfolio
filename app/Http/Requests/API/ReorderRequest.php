<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ReorderRequest extends FormRequest
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
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
            'category_id' => ['nullable', 'integer'],
        ];
    }
}
