<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePortfolioItemRequest extends FormRequest
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
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string', 'max:300'],
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('portfolio_categories', 'id')->where('user_id', $this->user()->id),
            ],
            'client_name' => ['nullable', 'string', 'max:150'],
            'project_url' => ['nullable', 'url', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
