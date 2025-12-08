<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\RatingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RatingService
{
    public function __construct(
        private readonly RatingRepositoryInterface $ratingRepository,
        private readonly CourseRepositoryInterface $courseRepository
    ) {}

    public function rateCourse(User $user, int $courseId, int $rating, ?string $review): array
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found',
            ];
        }

        // Prevent educators from rating their own courses
        if ($course->user_id === $user->id) {
            return [
                'success' => false,
                'message' => 'You cannot rate your own course',
            ];
        }

        $courseRating = $this->ratingRepository->createOrUpdateRating($user, $course, $rating, $review);

        return [
            'success' => true,
            'message' => 'Rating submitted successfully',
            'data' => $courseRating,
        ];
    }

    public function deleteRating(User $user, int $courseId): array
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found',
            ];
        }

        $existingRating = $this->ratingRepository->getUserRating($user, $course);

        if (!$existingRating) {
            return [
                'success' => false,
                'message' => 'No rating found',
            ];
        }

        $this->ratingRepository->deleteRating($user, $course);

        return [
            'success' => true,
            'message' => 'Rating deleted successfully',
        ];
    }

    public function getCourseRatings(int $courseId): array
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found',
            ];
        }

        $ratings = $this->ratingRepository->getCourseRatings($course);

        return [
            'success' => true,
            'data' => [
                'average_rating' => $course->average_rating,
                'ratings_count' => $course->ratings_count,
                'ratings' => $ratings,
            ],
        ];
    }

    public function getUserRating(User $user, int $courseId): array
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found',
            ];
        }

        $rating = $this->ratingRepository->getUserRating($user, $course);

        return [
            'success' => true,
            'data' => $rating,
        ];
    }
}
