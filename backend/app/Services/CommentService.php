<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly CourseRepositoryInterface $courseRepository
    ) {}

    public function createComment(User $user, int $courseId, string $comment, ?int $parentId = null): array
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found',
            ];
        }

        // Validate parent comment if provided
        if ($parentId) {
            $parentComment = $this->commentRepository->findById($parentId);

            if (!$parentComment) {
                return [
                    'success' => false,
                    'message' => 'Parent comment not found',
                ];
            }

            // Ensure parent comment belongs to the same course
            if ($parentComment->course_id !== $courseId) {
                return [
                    'success' => false,
                    'message' => 'Parent comment does not belong to this course',
                ];
            }
        }

        $commentData = [
            'user_id' => $user->id,
            'course_id' => $courseId,
            'comment' => $comment,
            'parent_id' => $parentId,
        ];

        $newComment = $this->commentRepository->create($commentData);

        return [
            'success' => true,
            'message' => 'Comment posted successfully',
            'data' => $newComment->load(['user', 'replies']),
        ];
    }

    public function updateComment(User $user, int $commentId, string $comment): array
    {
        $existingComment = $this->commentRepository->findById($commentId);

        if (!$existingComment) {
            return [
                'success' => false,
                'message' => 'Comment not found',
            ];
        }

        // Only comment owner can update
        if ($existingComment->user_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Unauthorized to update this comment',
            ];
        }

        $this->commentRepository->update($existingComment, ['comment' => $comment]);

        return [
            'success' => true,
            'message' => 'Comment updated successfully',
        ];
    }

    public function deleteComment(User $user, int $commentId): array
    {
        $comment = $this->commentRepository->findById($commentId);

        if (!$comment) {
            return [
                'success' => false,
                'message' => 'Comment not found',
            ];
        }

        // Only comment owner can delete
        if ($comment->user_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Unauthorized to delete this comment',
            ];
        }

        $this->commentRepository->delete($comment);

        return [
            'success' => true,
            'message' => 'Comment deleted successfully',
        ];
    }

    public function getCourseComments(int $courseId): array
    {
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return [
                'success' => false,
                'message' => 'Course not found',
            ];
        }

        $comments = $this->commentRepository->getCourseComments($course);

        return [
            'success' => true,
            'data' => $comments,
        ];
    }

    public function getUserComments(User $user): Collection
    {
        return $this->commentRepository->getUserComments($user);
    }

    public function likeComment(User $user, int $commentId): array
    {
        $comment = $this->commentRepository->findById($commentId);

        if (!$comment) {
            return [
                'success' => false,
                'message' => 'Comment not found',
            ];
        }

        // Check if already liked
        if ($this->commentRepository->isLikedBy($user, $comment)) {
            return [
                'success' => false,
                'message' => 'Comment already liked',
            ];
        }

        $this->commentRepository->likeComment($user, $comment);

        return [
            'success' => true,
            'message' => 'Comment liked successfully',
            'data' => ['likes_count' => $comment->fresh()->likes_count],
        ];
    }

    public function unlikeComment(User $user, int $commentId): array
    {
        $comment = $this->commentRepository->findById($commentId);

        if (!$comment) {
            return [
                'success' => false,
                'message' => 'Comment not found',
            ];
        }

        // Check if not liked
        if (!$this->commentRepository->isLikedBy($user, $comment)) {
            return [
                'success' => false,
                'message' => 'Comment not liked',
            ];
        }

        $this->commentRepository->unlikeComment($user, $comment);

        return [
            'success' => true,
            'message' => 'Comment unliked successfully',
            'data' => ['likes_count' => $comment->fresh()->likes_count],
        ];
    }
}
