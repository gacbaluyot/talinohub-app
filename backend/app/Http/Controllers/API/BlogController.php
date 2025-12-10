<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Repositories\Contracts\BlogRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    protected BlogRepositoryInterface $blogRepository;

    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function index(): JsonResponse
    {
        $blogs = $this->blogRepository->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $blogs
        ]);
    }

    public function store(StoreBlogRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['uuid'] = Str::uuid();
        $data['user_id'] = auth()->id();

        $blog = $this->blogRepository->store($data);

        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully',
            'data' => $blog
        ], 201);
    }

    public function show(string $uuid): JsonResponse
    {
        $blog = $this->blogRepository->findByUuid($uuid);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blog->load('user')
        ]);
    }

    public function update(UpdateBlogRequest $request, string $uuid): JsonResponse
    {
        $blog = $this->blogRepository->findByUuid($uuid);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        $updated = $this->blogRepository->update($blog->id, $request->validated());

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update blog'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully',
            'data' => $blog->fresh()
        ]);
    }

    public function destroy(string $uuid): JsonResponse
    {
        $blog = $this->blogRepository->findByUuid($uuid);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        $deleted = $this->blogRepository->delete($blog->id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete blog'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully'
        ]);
    }
}
