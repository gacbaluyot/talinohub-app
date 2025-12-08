<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

class CourseDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly string $title,
        public readonly ?string $slug = null,
        public readonly ?string $shortDescription = null,
        public readonly ?string $description = null,
        public readonly bool $isFree = true,
        public readonly ?float $price = null,
        public readonly ?UploadedFile $thumbnail = null,
        public readonly ?string $category = null,
        public readonly ?string $level = null,
        public readonly ?string $language = null,
        public readonly bool $isPublished = false,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            title: $data['title'],
            slug: $data['slug'] ?? null,
            shortDescription: $data['short_description'] ?? null,
            description: $data['description'] ?? null,
            isFree: $data['is_free'] ?? true,
            price: isset($data['price']) ? (float) $data['price'] : null,
            thumbnail: $data['thumbnail'] ?? null,
            category: $data['category'] ?? null,
            level: $data['level'] ?? null,
            language: $data['language'] ?? null,
            isPublished: $data['is_published'] ?? false,
        );
    }

    public function toArray(): array
    {
        $data = [
            'user_id' => $this->userId,
            'title' => $this->title,
            'is_free' => $this->isFree,
            'is_published' => $this->isPublished,
        ];

        if ($this->slug !== null) {
            $data['slug'] = $this->slug;
        }

        if ($this->shortDescription !== null) {
            $data['short_description'] = $this->shortDescription;
        }

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->price !== null) {
            $data['price'] = $this->price;
        }

        if ($this->thumbnail !== null) {
            $data['thumbnail'] = $this->thumbnail;
        }

        if ($this->category !== null) {
            $data['category'] = $this->category;
        }

        if ($this->level !== null) {
            $data['level'] = $this->level;
        }

        if ($this->language !== null) {
            $data['language'] = $this->language;
        }

        return $data;
    }
}
