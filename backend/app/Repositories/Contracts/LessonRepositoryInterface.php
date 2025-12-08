<?php

namespace App\Repositories\Contracts;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Collection;

interface LessonRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Lesson;

    public function findBySlug(string $slug): ?Lesson;

    public function create(array $data): Lesson;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getByCourse(int $courseId): Collection;

    public function getPreviewLessons(int $courseId): Collection;

    public function reorder(int $courseId, array $positions): bool;
}