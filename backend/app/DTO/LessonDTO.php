<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

readonly class LessonDTO
{
    public function __construct(
        public int           $courseId,
        public string        $title,
        public string        $type,
        public ?string       $slug = null,
        public ?int          $position = null,
        public ?string       $videoUrl = null,
        public ?UploadedFile $document = null,
        public ?int          $durationSeconds = null,
        public bool          $isPreview = false,
    ) {}

    /**
     * @param array $data
     * @return self
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            courseId: $data['course_id'],
            title: $data['title'],
            type: $data['type'],
            slug: $data['slug'] ?? null,
            position: isset($data['position']) ? (int) $data['position'] : null,
            videoUrl: $data['video_url'] ?? null,
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

        if ($this->document !== null) {
            $data['document'] = $this->document;
        }

        if ($this->durationSeconds !== null) {
            $data['duration_seconds'] = $this->durationSeconds;
        }

        return $data;
    }
}
