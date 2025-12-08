<?php

namespace App\Repositories;

use App\Models\Lesson;
use App\Repositories\Contracts\LessonRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LessonRepository implements LessonRepositoryInterface
{
    public function __construct(
        protected Lesson $model
    ) {}

    public function all(): Collection
    {
        return $this->model->with('course')->get();
    }

    public function find(int $id): ?Lesson
    {
        return $this->model->with('course')->find($id);
    }

    public function findBySlug(string $slug): ?Lesson
    {
        return $this->model->with('course')
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): Lesson
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $lesson = $this->model->find($id);

        if (!$lesson) {
            return false;
        }

        return $lesson->update($data);
    }

    public function delete(int $id): bool
    {
        $lesson = $this->model->find($id);

        if (!$lesson) {
            return false;
        }

        return $lesson->delete();
    }

    public function getByCourse(int $courseId): Collection
    {
        return $this->model->where('course_id', $courseId)
            ->orderBy('position')
            ->get();
    }

    public function getPreviewLessons(int $courseId): Collection
    {
        return $this->model->where('course_id', $courseId)
            ->where('is_preview', true)
            ->orderBy('position')
            ->get();
    }

    public function reorder(int $courseId, array $positions): bool
    {
        foreach ($positions as $lessonId => $position) {
            $this->model->where('id', $lessonId)
                ->where('course_id', $courseId)
                ->update(['position' => $position]);
        }

        return true;
    }
}
