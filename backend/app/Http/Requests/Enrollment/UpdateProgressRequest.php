<?php

namespace App\Http\Requests\Enrollment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'progress.required' => 'Progress is required',
            'progress.integer' => 'Progress must be a number',
            'progress.min' => 'Progress cannot be less than 0',
            'progress.max' => 'Progress cannot be greater than 100',
        ];
    }
}
