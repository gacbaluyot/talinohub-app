<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Repositories\Contracts\BlogRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogRepository implements BlogRepositoryInterface
{
    public function all(): Collection
    {
        return Blog::all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Blog::with("user")->paginate($perPage);
    }

    public function find(int $id): ?Blog
    {
        return Blog::findorFail($id);
    }

    public function findByUuid(string $uuid): ?Blog
    {
        return Blog::where("uuid", $uuid)->first();
    }

    public function store(array $data): Blog
    {
        return Blog::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $blog = $this->find($id);

        if (!$blog) {
            return false;
        }

        return $blog->update($data);
    }

    public function delete(int $id): bool
    {
        $blog = $this->find($id);

        if (!$blog) {
            return false;
        }

        return $blog->delete();
    }
}
