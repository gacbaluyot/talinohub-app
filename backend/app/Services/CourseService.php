<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourseService
{
    public function __construct(
        protected CourseRepositoryInterface $courseRepository
    ) {}

    public function getAllCourses(): Collection
    {
        return $this->courseRepository->all();
    }

    public function getPaginatedCourses(int $perPage = 15): LengthAwarePaginator
    {
        return $this->courseRepository->paginate($perPage);
    }

    public function getCourseById(int $id): ?Course
    {
        return $this->courseRepository->find($id);
    }

    public function getCourseBySlug(string $slug): ?Course
    {
        return $this->courseRepository->findBySlug($slug);
    }

    public function createCourse(array $data): Course
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                $data['thumbnail_path'] = $data['thumbnail']->store('courses/thumbnails', 'public');
                unset($data['thumbnail']);
            }

            return $this->courseRepository->create($data);
        });
    }

    public function updateCourse(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $course = $this->courseRepository->find($id);

            if (!$course) {
                return false;
            }

            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                // Delete old thumbnail
                if ($course->thumbnail_path) {
                    Storage::disk('public')->delete($course->thumbnail_path);
                }

                $data['thumbnail_path'] = $data['thumbnail']->store('courses/thumbnails', 'public');
                unset($data['thumbnail']);
            }

            // Auto-set published_at when publishing
            if (isset($data['is_published']) && $data['is_published'] && !$course->published_at) {
                $data['published_at'] = now();
            }

            return $this->courseRepository->update($id, $data);
        });
    }

    public function deleteCourse(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $course = $this->courseRepository->find($id);

            if (!$course) {
                return false;
            }

            // Delete thumbnail
            if ($course->thumbnail_path) {
                Storage::disk('public')->delete($course->thumbnail_path);
            }

            return $this->courseRepository->delete($id);
        });
    }

    public function getPublishedCourses(): Collection
    {
        return $this->courseRepository->getPublished();
    }

    public function getCoursesByEducator(int $userId): Collection
    {
        return $this->courseRepository->getByEducator($userId);
    }

    public function getCoursesByCategory(string $category): Collection
    {
        return $this->courseRepository->getByCategory($category);
    }

    public function getCoursesByLevel(string $level): Collection
    {
        return $this->courseRepository->getByLevel($level);
    }

    public function publishCourse(int $id): bool
    {
        return $this->updateCourse($id, [
            'is_published' => true,
            'published_at' => now()
        ]);
    }

    public function unpublishCourse(int $id): bool
    {
        return $this->updateCourse($id, [
            'is_published' => false
        ]);
    }
}
