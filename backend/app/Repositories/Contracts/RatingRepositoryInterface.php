<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use App\Models\CourseRating;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface RatingRepositoryInterface
{
    public function createOrUpdateRating(User $user, Course $course, int $rating, ?string $review): CourseRating;

    public function deleteRating(User $user, Course $course): void;

    public function getUserRating(User $user, Course $course): ?CourseRating;

    public function getCourseRatings(Course $course): Collection;

    public function updateCourseRatingStats(Course $course): void;
}
