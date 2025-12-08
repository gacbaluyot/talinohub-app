<?php

namespace App\Http\Controllers\API;

use App\DTO\LessonDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\ReorderLessonsRequest;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Services\LessonService;
use Illuminate\Http\JsonResponse;

class LessonController extends Controller
{
    public function __construct(
        protected LessonService $lessonService
    ) {}

    public function index(): JsonResponse
    {
        $lessons = $this->lessonService->getAllLessons();

        return response()->json([
            'success' => true,
            'data' => $lessons
        ]);
    }

    public function show(string $identifier): JsonResponse
    {
        // Check if identifier is numeric (ID) or string (slug)
        $lesson = is_numeric($identifier)
            ? $this->lessonService->getLessonById((int) $identifier)
            : $this->lessonService->getLessonBySlug($identifier);

        if (!$lesson) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $lesson
        ]);
    }

    public function store(StoreLessonRequest $request): JsonResponse
    {
        $dto = LessonDTO::fromRequest($request->validated());

        $lesson = $this->lessonService->createLesson($dto->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Lesson created successfully',
            'data' => $lesson
        ], 201);
    }

    public function update(UpdateLessonRequest $request, int $id): JsonResponse
    {
        $updated = $this->lessonService->updateLesson($id, $request->validated());

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lesson updated successfully'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->lessonService->deleteLesson($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lesson deleted successfully'
        ]);
    }

    public function byCourse(int $courseId): JsonResponse
    {
        // Returns all lessons for free courses, only preview lessons for paid courses
        $lessons = $this->lessonService->getPublicLessons($courseId);

        return response()->json([
            'success' => true,
            'data' => $lessons
        ]);
    }

    public function preview(int $courseId): JsonResponse
    {
        $lessons = $this->lessonService->getPreviewLessons($courseId);

        return response()->json([
            'success' => true,
            'data' => $lessons
        ]);
    }

    public function reorder(ReorderLessonsRequest $request, int $courseId): JsonResponse
    {
        $reordered = $this->lessonService->reorderLessons(
            $courseId,
            $request->validated()['positions']
        );

        if (!$reordered) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder lessons'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lessons reordered successfully'
        ]);
    }

    /**
     * Get all lessons for an enrolled course (protected route)
     * This allows enrolled users to access all lessons regardless of course price
     */
    public function enrolledCourseLessons(int $courseId): JsonResponse
    {
        $lessons = $this->lessonService->getLessonsByCourse($courseId);

        return response()->json([
            'success' => true,
            'data' => $lessons
        ]);
    }
}
