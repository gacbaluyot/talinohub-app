<?php

namespace App\Services;

use App\Models\Lesson;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\LessonRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LessonService
{
    public function __construct(
        protected LessonRepositoryInterface $lessonRepository,
        protected CourseRepositoryInterface $courseRepository
    ) {}

    public function getAllLessons(): Collection
    {
        return $this->lessonRepository->all();
    }

    public function getLessonById(int $id): ?Lesson
    {
        return $this->lessonRepository->find($id);
    }

    public function getLessonBySlug(string $slug): ?Lesson
    {
        return $this->lessonRepository->findBySlug($slug);
    }

    public function createLesson(array $data): Lesson
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['document']) && $data['document'] instanceof \Illuminate\Http\UploadedFile) {
                $data['document_path'] = $data['document']->store('lessons/documents', 'public');
                unset($data['document']);
            }

            return $this->lessonRepository->create($data);
        });
    }

    public function updateLesson(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $lesson = $this->lessonRepository->find($id);

            if (!$lesson) {
                return false;
            }

            if (isset($data['document']) && $data['document'] instanceof \Illuminate\Http\UploadedFile) {
                // Delete old document
                if ($lesson->document_path) {
                    Storage::disk('public')->delete($lesson->document_path);
                }

                $data['document_path'] = $data['document']->store('lessons/documents', 'public');
                unset($data['document']);
            }

            return $this->lessonRepository->update($id, $data);
        });
    }

    public function deleteLesson(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $lesson = $this->lessonRepository->find($id);

            if (!$lesson) {
                return false;
            }

            // Delete document
            if ($lesson->document_path) {
                Storage::disk('public')->delete($lesson->document_path);
            }

            return $this->lessonRepository->delete($id);
        });
    }

    public function getLessonsByCourse(int $courseId): Collection
    {
        return $this->lessonRepository->getByCourse($courseId);
    }

    public function getPublicLessons(int $courseId): Collection
    {
        // For free courses, return all lessons. For paid courses, return only preview lessons
        $course = $this->courseRepository->findById($courseId);

        if (!$course) {
            return collect();
        }

        // If course is free, return all lessons
        if ($course->is_free) {
            return $this->lessonRepository->getByCourse($courseId);
        }

        // Otherwise, return only preview lessons
        return $this->lessonRepository->getPreviewLessons($courseId);
    }

    public function getPreviewLessons(int $courseId): Collection
    {
        return $this->lessonRepository->getPreviewLessons($courseId);
    }

    public function reorderLessons(int $courseId, array $positions): bool
    {
        return DB::transaction(function () use ($courseId, $positions) {
            return $this->lessonRepository->reorder($courseId, $positions);
        });
    }
}
