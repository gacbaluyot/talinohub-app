<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentService
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollmentRepository,
        private readonly CourseRepositoryInterface $courseRepository
    ) {}

    public function enrollUserInCourse(User $user, int $courseId): array
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found',
            ];
        }

        // Check if course is published
        if (!$course->is_published) {
            return [
                'success' => false,
                'message' => 'Cannot enroll in unpublished course',
            ];
        }

        // Check if user owns the course
        if ($course->user_id === $user->id) {
            return [
                'success' => false,
                'message' => 'You cannot enroll in your own course',
            ];
        }

        // Check if already enrolled
        if ($this->enrollmentRepository->isEnrolled($user, $course)) {
            return [
                'success' => false,
                'message' => 'Already enrolled in this course',
            ];
        }

        $this->enrollmentRepository->enroll($user, $course);

        return [
            'success' => true,
            'message' => 'Successfully enrolled in course',
            'data' => $course->load(['educator', 'lessons']),
        ];
    }

    public function unenrollUserFromCourse(User $user, int $courseId): array
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found',
            ];
        }

        if (!$this->enrollmentRepository->isEnrolled($user, $course)) {
            return [
                'success' => false,
                'message' => 'Not enrolled in this course',
            ];
        }

        $this->enrollmentRepository->unenroll($user, $course);

        return [
            'success' => true,
            'message' => 'Successfully unenrolled from course',
        ];
    }

    public function getUserEnrolledCourses(User $user): Collection
    {
        return $this->enrollmentRepository->getUserEnrolledCourses($user);
    }

    public function getCourseStudents(int $courseId): ?Collection
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return null;
        }

        return $this->enrollmentRepository->getCourseStudents($course);
    }

    public function updateCourseProgress(User $user, int $courseId, int $progress): array
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found',
            ];
        }

        if (!$this->enrollmentRepository->isEnrolled($user, $course)) {
            return [
                'success' => false,
                'message' => 'Not enrolled in this course',
            ];
        }

        $this->enrollmentRepository->updateProgress($user, $course, $progress);

        // Auto-mark as completed if progress is 100
        if ($progress === 100) {
            $this->enrollmentRepository->markAsCompleted($user, $course);
        }

        return [
            'success' => true,
            'message' => 'Progress updated successfully',
            'data' => ['progress' => $progress],
        ];
    }

    public function addToFavorites(User $user, int $courseId): array
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found',
            ];
        }

        if ($this->enrollmentRepository->isFavorited($user, $course)) {
            return [
                'success' => false,
                'message' => 'Course already in favorites',
            ];
        }

        $this->enrollmentRepository->addToFavorites($user, $course);

        return [
            'success' => true,
            'message' => 'Course added to favorites',
        ];
    }

    public function removeFromFavorites(User $user, int $courseId): array
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found',
            ];
        }

        if (!$this->enrollmentRepository->isFavorited($user, $course)) {
            return [
                'success' => false,
                'message' => 'Course not in favorites',
            ];
        }

        $this->enrollmentRepository->removeFromFavorites($user, $course);

        return [
            'success' => true,
            'message' => 'Course removed from favorites',
        ];
    }

    public function getUserFavorites(User $user): Collection
    {
        return $this->enrollmentRepository->getUserFavoriteCourses($user);
    }
}
