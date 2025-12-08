<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\CourseRating;
use App\Models\User;
use App\Repositories\Contracts\RatingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RatingRepository implements RatingRepositoryInterface
{
    public function createOrUpdateRating(User $user, Course $course, int $rating, ?string $review): CourseRating
    {
        $courseRating = CourseRating::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'rating' => $rating,
                'review' => $review,
            ]
        );

        $this->updateCourseRatingStats($course);

        return $courseRating;
    }

    public function deleteRating(User $user, Course $course): void
    {
        CourseRating::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->delete();

        $this->updateCourseRatingStats($course);
    }

    public function getUserRating(User $user, Course $course): ?CourseRating
    {
        return CourseRating::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
    }

    public function getCourseRatings(Course $course): Collection
    {
        return $course->ratings()
            ->with('user:id,name')
            ->latest()
            ->get();
    }

    public function updateCourseRatingStats(Course $course): void
    {
        $stats = CourseRating::where('course_id', $course->id)
            ->selectRaw('AVG(rating) as average, COUNT(*) as count')
            ->first();

        $course->update([
            'average_rating' => $stats->average ? round($stats->average, 2) : 0,
            'ratings_count' => $stats->count,
        ]);
    }
}
