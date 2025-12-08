<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService
    ) {}

    /**
     * Get all comments for a course
     */
    public function courseComments(int $courseId): JsonResponse
    {
        $result = $this->commentService->getCourseComments($courseId);

        return response()->json($result, $result['success'] ? 200 : 404);
    }

    /**
     * Create a new comment on a course
     */
    public function store(int $courseId, StoreCommentRequest $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->commentService->createComment(
            $user,
            $courseId,
            $request->validated('comment'),
            $request->validated('parent_id')
        );

        return response()->json($result, $result['success'] ? 201 : 400);
    }

    /**
     * Update a comment
     */
    public function update(int $commentId, UpdateCommentRequest $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->commentService->updateComment(
            $user,
            $commentId,
            $request->validated('comment')
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Delete a comment
     */
    public function delete(int $commentId, Request $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->commentService->deleteComment($user, $commentId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Get authenticated user's comments
     */
    public function myComments(Request $request): JsonResponse
    {
        $user = $request->user();
        $comments = $this->commentService->getUserComments($user);

        return response()->json([
            'success' => true,
            'data' => $comments,
        ]);
    }

    /**
     * Like a comment
     */
    public function like(int $commentId, Request $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->commentService->likeComment($user, $commentId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Unlike a comment
     */
    public function unlike(int $commentId, Request $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->commentService->unlikeComment($user, $commentId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
