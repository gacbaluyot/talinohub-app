<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\CourseComment;
use App\Models\User;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository implements CommentRepositoryInterface
{
    public function create(array $data): CourseComment
    {
        return CourseComment::create($data);
    }

    public function update(CourseComment $comment, array $data): bool
    {
        return $comment->update($data);
    }

    public function delete(CourseComment $comment): bool
    {
        return $comment->delete();
    }

    public function findById(int $id): ?CourseComment
    {
        return CourseComment::with(['user', 'replies'])->find($id);
    }

    public function getCourseComments(Course $course): Collection
    {
        return $course->comments()
            ->topLevel()
            ->with(['user', 'replies'])
            ->latest()
            ->get();
    }

    public function getUserComments(User $user): Collection
    {
        return $user->courseComments()
            ->with(['course:id,title,slug'])
            ->latest()
            ->get();
    }

    public function likeComment(User $user, CourseComment $comment): void
    {
        if (!$this->isLikedBy($user, $comment)) {
            $comment->likes()->attach($user->id, [
                'liked_at' => now(),
            ]);
            $this->updateLikesCount($comment);
        }
    }

    public function unlikeComment(User $user, CourseComment $comment): void
    {
        $comment->likes()->detach($user->id);
        $this->updateLikesCount($comment);
    }

    public function isLikedBy(User $user, CourseComment $comment): bool
    {
        return $comment->likes()->where('user_id', $user->id)->exists();
    }

    public function updateLikesCount(CourseComment $comment): void
    {
        $count = $comment->likes()->count();
        $comment->update(['likes_count' => $count]);
    }
}
