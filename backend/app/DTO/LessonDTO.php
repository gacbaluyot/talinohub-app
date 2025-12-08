<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

class LessonDTO
{
    public function __construct(
        public readonly int $courseId,
        public readonly string $title,
        public readonly string $type,
        public readonly ?string $slug = null,
        public readonly ?int $position = null,
        public readonly ?string $videoUrl = null,
        public readonly ?string $videoProvider = null,
        public readonly ?UploadedFile $document = null,
        public readonly ?int $durationSeconds = null,
        public readonly bool $isPreview = false,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            courseId: $data['course_id'],
            title: $data['title'],
            type: $data['type'],
            slug: $data['slug'] ?? null,
            position: isset($data['position']) ? (int) $data['position'] : null,
            videoUrl: $data['video_url'] ?? null,
            videoProvider: $data['video_provider'] ?? null,
            document: $data['document'] ?? null,
            durationSeconds: isset($data['duration_seconds']) ? (int) $data['duration_seconds'] : null,
            isPreview: $data['is_preview'] ?? false,
        );
    }

    public function toArray(): array
    {
        $data = [
            'course_id' => $this->courseId,
            'title' => $this->title,
            'type' => $this->type,
            'is_preview' => $this->isPreview,
        ];

        if ($this->slug !== null) {
            $data['slug'] = $this->slug;
        }

        if ($this->position !== null) {
            $data['position'] = $this->position;
        }

        if ($this->videoUrl !== null) {
            $data['video_url'] = $this->videoUrl;
        }

        if ($this->videoProvider !== null) {
            $data['video_provider'] = $this->videoProvider;
        }

        if ($this->document !== null) {
            $data['document'] = $this->document;
        }

        if ($this->durationSeconds !== null) {
            $data['duration_seconds'] = $this->durationSeconds;
        }

        return $data;
    }
}
