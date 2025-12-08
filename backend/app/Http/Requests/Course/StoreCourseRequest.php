<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:courses,slug',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_free' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:100',
            'level' => 'nullable|string|in:beginner,intermediate,advanced',
            'language' => 'nullable|string|max:50',
            'is_published' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The educator is required.',
            'user_id.exists' => 'The selected educator does not exist.',
            'title.required' => 'The course title is required.',
            'title.max' => 'The course title must not exceed 255 characters.',
            'short_description.max' => 'The short description must not exceed 500 characters.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be at least 0.',
            'thumbnail.image' => 'The thumbnail must be an image file.',
            'thumbnail.max' => 'The thumbnail must not exceed 2MB.',
            'level.in' => 'The level must be beginner, intermediate, or advanced.',
        ];
    }
}
