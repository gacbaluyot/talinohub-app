<?php

namespace App\Http\Controllers\API;

use App\DTO\CourseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);

        $courses = $this->courseService->getPaginatedCourses($perPage);

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function published(): JsonResponse
    {
        $courses = $this->courseService->getPublishedCourses();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function show(string $identifier): JsonResponse
    {
        // Check if identifier is numeric (ID) or string (slug)
        $course = is_numeric($identifier)
            ? $this->courseService->getCourseById((int) $identifier)
            : $this->courseService->getCourseBySlug($identifier);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $course
        ]);
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        $dto = CourseDTO::fromRequest($request->validated());

        $course = $this->courseService->createCourse($dto->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully',
            'data' => $course
        ], 201);
    }

    public function update(UpdateCourseRequest $request, int $id): JsonResponse
    {
        $updated = $this->courseService->updateCourse($id, $request->validated());

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->courseService->deleteCourse($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully'
        ]);
    }

    public function educator(int $userId): JsonResponse
    {
        $courses = $this->courseService->getCoursesByEducator($userId);

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function category(string $category): JsonResponse
    {
        $courses = $this->courseService->getCoursesByCategory($category);

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function level(string $level): JsonResponse
    {
        $courses = $this->courseService->getCoursesByLevel($level);

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function publish(int $id): JsonResponse
    {
        $published = $this->courseService->publishCourse($id);

        if (!$published) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course published successfully'
        ]);
    }

    public function unpublish(int $id): JsonResponse
    {
        $unpublished = $this->courseService->unpublishCourse($id);

        if (!$unpublished) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course unpublished successfully'
        ]);
    }
}
