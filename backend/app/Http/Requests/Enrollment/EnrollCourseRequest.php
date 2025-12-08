<?php

namespace App\Http\Requests\Enrollment;

use Illuminate\Foundation\Http\FormRequest;

class EnrollCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization checked in controller/service
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'Course ID is required',
            'course_id.exists' => 'Course not found',
        ];
    }
}
