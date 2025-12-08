<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Enrollment\EnrollCourseRequest;
use App\Http\Requests\Enrollment\UpdateProgressRequest;
use App\Services\EnrollmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct(
        private readonly EnrollmentService $enrollmentService
    ) {}

    /**
     * Enroll authenticated user in a course
     */
    public function enroll(EnrollCourseRequest $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->enrollmentService->enrollUserInCourse($user, $request->validated('course_id'));

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Unenroll authenticated user from a course
     */
    public function unenroll(int $courseId, Request $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->enrollmentService->unenrollUserFromCourse($user, $courseId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Get all courses the authenticated user is enrolled in
     */
    public function myEnrollments(Request $request): JsonResponse
    {
        $user = $request->user();
        $courses = $this->enrollmentService->getUserEnrolledCourses($user);

        return response()->json([
            'success' => true,
            'data' => $courses,
        ]);
    }

    /**
     * Get all students enrolled in a course (for educators)
     */
    public function courseStudents(int $courseId, Request $request): JsonResponse
    {
        $students = $this->enrollmentService->getCourseStudents($courseId);

        if ($students === null) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $students,
        ]);
    }

    /**
     * Update progress for a course enrollment
     */
    public function updateProgress(int $courseId, UpdateProgressRequest $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->enrollmentService->updateCourseProgress(
            $user,
            $courseId,
            $request->validated('progress')
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Add course to favorites
     */
    public function addToFavorites(int $courseId, Request $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->enrollmentService->addToFavorites($user, $courseId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Remove course from favorites
     */
    public function removeFromFavorites(int $courseId, Request $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->enrollmentService->removeFromFavorites($user, $courseId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Get user's favorite courses
     */
    public function myFavorites(Request $request): JsonResponse
    {
        $user = $request->user();
        $favorites = $this->enrollmentService->getUserFavorites($user);

        return response()->json([
            'success' => true,
            'data' => $favorites,
        ]);
    }
}
