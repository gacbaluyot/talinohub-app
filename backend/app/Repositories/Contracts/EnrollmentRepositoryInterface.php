<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface EnrollmentRepositoryInterface
{
    public function enroll(User $user, Course $course): void;

    public function unenroll(User $user, Course $course): void;

    public function isEnrolled(User $user, Course $course): bool;

    public function getUserEnrolledCourses(User $user): Collection;

    public function getCourseStudents(Course $course): Collection;

    public function updateProgress(User $user, Course $course, int $progress): void;

    public function markAsCompleted(User $user, Course $course): void;

    public function addToFavorites(User $user, Course $course): void;

    public function removeFromFavorites(User $user, Course $course): void;

    public function isFavorited(User $user, Course $course): bool;

    public function getUserFavoriteCourses(User $user): Collection;
}
