<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Course;

    public function findBySlug(string $slug): ?Course;

    public function create(array $data): Course;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getPublished(): Collection;

    public function getByEducator(int $userId): Collection;

    public function getByCategory(string $category): Collection;

    public function getByLevel(string $level): Collection;
}