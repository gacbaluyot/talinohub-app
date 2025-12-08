<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer', 'exists:course_comments,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'Comment text is required',
            'comment.max' => 'Comment cannot exceed 2000 characters',
            'parent_id.exists' => 'Parent comment not found',
        ];
    }
}
