<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rating\RateCourseRequest;
use App\Services\RatingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function __construct(
        private readonly RatingService $ratingService
    ) {}

    /**
     * Rate a course
     */
    public function rate(int $courseId, RateCourseRequest $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->ratingService->rateCourse(
            $user,
            $courseId,
            $request->validated('rating'),
            $request->validated('review')
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Delete user's rating for a course
     */
    public function delete(int $courseId, Request $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->ratingService->deleteRating($user, $courseId);

        return response()->json($result, $result['success'] ? 200 : 404);
    }

    /**
     * Get all ratings for a course
     */
    public function courseRatings(int $courseId): JsonResponse
    {
        $result = $this->ratingService->getCourseRatings($courseId);

        return response()->json($result, $result['success'] ? 200 : 404);
    }

    /**
     * Get authenticated user's rating for a course
     */
    public function myRating(int $courseId, Request $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->ratingService->getUserRating($user, $courseId);

        return response()->json($result, $result['success'] ? 200 : 404);
    }
}
