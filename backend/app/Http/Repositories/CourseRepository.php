<?php

namespace App\Repositories;

use App\Helpers\UploadFiles;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Interfaces\CourseInterface;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseRepository implements CourseInterface
{
    public function index(Request $request): JsonResponse
    {
        $seacrh = $request->query('search', '');

        $rowsPerPage = $request->query('rowsPerPage', 10);

        $courses = Course::with(['instructor', 'files', 'videos']);

        if (!empty($seacrh)) {
            $courses->where('title', 'like', '%' . $seacrh . '%')
                ->orWhere('description', 'like', '%' . $seacrh . '%');
        }

        $paginatedCourses = $courses->paginate($rowsPerPage);

        return response()->json([
            'courses' => $paginatedCourses->items(),
            'current_page' => $paginatedCourses->currentPage(),
            'last_page' => $paginatedCourses->lastPage(),
            'per_page' => $paginatedCourses->perPage(),
            'total' => $paginatedCourses->total(),
        ]);
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = Course::create([
            'uuid' => Str::uuid(),
            'instructor_id' => $request->input('instructor_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ]);

        $videos = $request->file('videos', []);
        $files = $request->file('files', []);

        foreach ($videos as $video) {
            $uploadedVideo = (new UploadFiles())->uploadFiles('courses/videos', $video);
            $course->videos()->create([
                'slug' => Str::random(32),
                'filename' => $uploadedVideo['filename'],
                'path' => $uploadedVideo['path'],
            ]);
        }

        foreach ($files as $file) {
            $uploadedFile = (new UploadFiles())->uploadFiles('courses/files', $file);
            $course->files()->create([
                'slug' => Str::random(32),
                'filename' => $uploadedFile['filename'],
                'path' => $uploadedFile['path'],
            ]);
        }

        return response()->json(['message' => 'Course created successfully', 'course' => $course], 201);
    }

    public function update(UpdateCourseRequest $request): JsonResponse
    {
        $authUser = Auth::user();

        $course = Course::where('uuid', $request->input('uuid'))
        ->where('instructor_id', $authUser->id)
        ->firstOrFail();

        $videos = $request->file('videos', []);
        $files = $request->file('files', []);

        $course->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ]);

        foreach ($videos as $video) {
            $uploadedVideo = (new UploadFiles())->uploadFiles('courses/videos', $video);
            $course->videos()->create([
                'filename' => $uploadedVideo['filename'],
                'path' => $uploadedVideo['path'],
            ]);
        }

        foreach ($files as $file) {
            $uploadedFile = (new UploadFiles())->uploadFiles('courses/files', $file);
            $course->files()->create([
                'filename' => $uploadedFile['filename'],
                'path' => $uploadedFile['path'],
            ]);
        }

        return response()->json(['message' => 'Course updated successfully', 'course' => $course], 200);
    }

    public function delete(string $uuid): JsonResponse
    {
        $authUser = Auth::user();

        $course = Course::where('uuid', $uuid)
        ->where('instructor_id', $authUser->id)
        ->firstOrFail();

        $course->files()->delete();

        $course->videos()->delete();

        $course->delete();

        return response()->json(['message' => 'Course deleted successfully'], 200);
    }

    public function deleteVideo(string $uuid, string $slug): JsonResponse
    {
        $course = Course::where('uuid', $uuid)->firstOrFail();

        $video = $course->videos()->where('slug', $slug)->firstOrFail();

        $video->delete();
        
        return response()->json(['message' => 'Video deleted successfully'], 200);
    }

    public function updateVideo(string $uuid, string $slug): JsonResponse
    {
        $course = Course::where('uuid', $uuid)->firstOrFail();

        $video = $course->videos()->where('slug', $slug)->firstOrFail();

        $video->update([
            'is_free' => ! $video->is_free,
        ]);

        return response()->json(['message' => 'Video updated successfully'], 200);
    }

    public function deleteFile(string $uuid, string $slug): JsonResponse
    {
        $course = Course::where('uuid', $uuid)->firstOrFail();

        $file = $course->files()->where('slug', $slug)->firstOrFail();

        $file->delete();
        
        return response()->json(['message' => 'File deleted successfully'], 200);
    }
}