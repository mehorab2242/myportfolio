<?php

namespace App\Http\Requests\API;

use App\Models\Skill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSkillRequest extends FormRequest
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
                'sometimes',
                'required',
                'integer',
                Rule::exists('skill_categories', 'id')->where('user_id', $this->user()->id),
            ],
            'name' => ['sometimes', 'required', 'string', 'max:120'],
            'level' => ['nullable', 'string', 'max:50'],
            'level_type' => ['sometimes', 'required', Rule::in(Skill::LEVEL_TYPES)],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
