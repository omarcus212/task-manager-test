<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,archived',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The project name is required.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be active or archived.',
        ];
    }
}
