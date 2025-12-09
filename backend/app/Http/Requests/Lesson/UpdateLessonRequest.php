<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $lessonId = $this->route('id');

        return [
            'course_id' => 'sometimes|exists:courses,id',
            'title' => 'sometimes|string|max:255',
            'slug' => 'nullable|string|unique:lessons,slug,' . $lessonId,
            'position' => 'nullable|integer|min:1',
            'type' => 'sometimes|string|in:video,document,text',
            'video_url' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'duration_seconds' => 'nullable|integer|min:0',
            'is_preview' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.exists' => 'The selected course does not exist.',
            'title.max' => 'The lesson title must not exceed 255 characters.',
            'type.in' => 'The lesson type must be video, document, or text.',
            'document.mimes' => 'The document must be a PDF, DOC, DOCX, PPT, or PPTX file.',
            'document.max' => 'The document must not exceed 10MB.',
            'duration_seconds.integer' => 'The duration must be a valid number.',
            'duration_seconds.min' => 'The duration must be at least 0.',
            'position.min' => 'The position must be at least 1.',
        ];
    }
}
