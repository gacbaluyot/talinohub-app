<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use App\Models\CourseComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    public function create(array $data): CourseComment;

    public function update(CourseComment $comment, array $data): bool;

    public function delete(CourseComment $comment): bool;

    public function findById(int $id): ?CourseComment;

    public function getCourseComments(Course $course): Collection;

    public function getUserComments(User $user): Collection;

    public function likeComment(User $user, CourseComment $comment): void;

    public function unlikeComment(User $user, CourseComment $comment): void;

    public function isLikedBy(User $user, CourseComment $comment): bool;

    public function updateLikesCount(CourseComment $comment): void;
}
