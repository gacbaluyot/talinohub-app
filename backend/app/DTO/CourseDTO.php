<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

readonly class CourseDTO
{
    public function __construct(
        public int           $userId,
        public string        $title,
        public ?string       $slug = null,
        public ?string       $shortDescription = null,
        public ?string       $description = null,
        public bool          $isFree = true,
        public ?float        $price = null,
        public ?UploadedFile $thumbnail = null,
        public ?string       $category = null,
        public ?string       $level = null,
        public ?string       $language = null,
        public bool          $isPublished = false,
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
