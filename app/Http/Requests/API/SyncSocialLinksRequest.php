<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SyncSocialLinksRequest extends FormRequest
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
            'links' => ['required', 'array', 'min:1'],
            'links.*.id' => ['nullable', 'integer', 'exists:social_links,id'],
            'links.*.platform' => ['required', 'string', 'max:100'],
            'links.*.url' => ['required', 'url', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'links.*.url.url' => 'Each social link must be a valid URL.',
        ];
    }
}
