<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\User;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function enroll(User $user, Course $course): void
    {
        if (!$this->isEnrolled($user, $course)) {
            $user->enrolledCourses()->attach($course->id, [
                'enrolled_at' => now(),
                'progress' => 0,
            ]);
        }
    }

    public function unenroll(User $user, Course $course): void
    {
        $user->enrolledCourses()->detach($course->id);
    }

    public function isEnrolled(User $user, Course $course): bool
    {
        return $user->enrolledCourses()->where('course_id', $course->id)->exists();
    }

    public function getUserEnrolledCourses(User $user): Collection
    {
        return $user->enrolledCourses()
            ->with(['educator', 'lessons'])
            ->get();
    }

    public function getCourseStudents(Course $course): Collection
    {
        return $course->students()->get();
    }

    public function updateProgress(User $user, Course $course, int $progress): void
    {
        $user->enrolledCourses()->updateExistingPivot($course->id, [
            'progress' => $progress,
        ]);
    }

    public function markAsCompleted(User $user, Course $course): void
    {
        $user->enrolledCourses()->updateExistingPivot($course->id, [
            'progress' => 100,
            'completed_at' => now(),
        ]);
    }

    public function addToFavorites(User $user, Course $course): void
    {
        if (!$this->isFavorited($user, $course)) {
            $user->favoriteCourses()->attach($course->id, [
                'favorited_at' => now(),
            ]);
        }
    }

    public function removeFromFavorites(User $user, Course $course): void
    {
        $user->favoriteCourses()->detach($course->id);
    }

    public function isFavorited(User $user, Course $course): bool
    {
        return $user->favoriteCourses()->where('course_id', $course->id)->exists();
    }

    public function getUserFavoriteCourses(User $user): Collection
    {
        return $user->favoriteCourses()
            ->with(['educator', 'lessons'])
            ->get();
    }
}
