<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'instructor_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'videos' => 'nullable|array',
            'videos.*' => 'file|mimes:mp4,mov,avi,mkv',
            'videos.*.is_free' => 'required|boolean',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip',
            'price' => 'required|numeric|min:0',
        ];
    }
}
