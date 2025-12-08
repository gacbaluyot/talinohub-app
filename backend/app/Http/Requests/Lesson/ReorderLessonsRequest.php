<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class ReorderLessonsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'positions' => 'required|array',
            'positions.*' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'positions.required' => 'The positions array is required.',
            'positions.array' => 'The positions must be an array.',
            'positions.*.integer' => 'Each position must be an integer.',
            'positions.*.min' => 'Each position must be at least 1.',
        ];
    }
}
