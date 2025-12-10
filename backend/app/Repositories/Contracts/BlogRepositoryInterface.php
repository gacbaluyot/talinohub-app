<?php

namespace App\Repositories\Contracts;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BlogRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Blog;

    public function findByUuid(string $uuid): ?Blog;

    public function store(array $data): Blog;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
