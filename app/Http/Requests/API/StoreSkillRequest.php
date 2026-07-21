<?php

namespace App\Http\Requests\API;

use App\Models\Skill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSkillRequest extends FormRequest
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
            'category_id' => [
                'required',
                'integer',
                Rule::exists('skill_categories', 'id')->where('user_id', $this->user()->id),
            ],
            'name' => ['required', 'string', 'max:120'],
            'level' => ['nullable', 'string', 'max:50'],
            'level_type' => ['required', Rule::in(Skill::LEVEL_TYPES)],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
