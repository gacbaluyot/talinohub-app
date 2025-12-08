<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseRepository implements CourseRepositoryInterface
{
    public function __construct(
        protected Course $model
    ) {}

    public function all(): Collection
    {
        return $this->model->with(['educator', 'lessons'])->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['educator', 'lessons'])
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): ?Course
    {
        return $this->model->with(['educator', 'lessons'])->find($id);
    }

    public function findBySlug(string $slug): ?Course
    {
        return $this->model->with(['educator', 'lessons'])
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): Course
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $course = $this->model->find($id);

        if (!$course) {
            return false;
        }

        return $course->update($data);
    }

    public function delete(int $id): bool
    {
        $course = $this->model->find($id);

        if (!$course) {
            return false;
        }

        return $course->delete();
    }

    public function getPublished(): Collection
    {
        return $this->model->with(['educator', 'lessons'])
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->get();
    }

    public function getByEducator(int $userId): Collection
    {
        return $this->model->with(['educator', 'lessons'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function getByCategory(string $category): Collection
    {
        return $this->model->with(['educator', 'lessons'])
            ->where('category', $category)
            ->where('is_published', true)
            ->latest('published_at')
            ->get();
    }

    public function getByLevel(string $level): Collection
    {
        return $this->model->with(['educator', 'lessons'])
            ->where('level', $level)
            ->where('is_published', true)
            ->latest('published_at')
            ->get();
    }
}
