<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:lessons,slug',
            'position' => 'nullable|integer|min:1',
            'type' => 'required|string|in:video,document,text',
            'video_url' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'duration_seconds' => 'nullable|integer|min:0',
            'is_preview' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'The course is required.',
            'course_id.exists' => 'The selected course does not exist.',
            'title.required' => 'The lesson title is required.',
            'title.max' => 'The lesson title must not exceed 255 characters.',
            'type.required' => 'The lesson type is required.',
            'type.in' => 'The lesson type must be video, document, or text.',
            'document.mimes' => 'The document must be a PDF, DOC, DOCX, PPT, or PPTX file.',
            'document.max' => 'The document must not exceed 10MB.',
            'duration_seconds.integer' => 'The duration must be a valid number.',
            'duration_seconds.min' => 'The duration must be at least 0.',
            'position.min' => 'The position must be at least 1.',
        ];
    }
}
