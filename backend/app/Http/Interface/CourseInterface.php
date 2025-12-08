<?php

namespace App\Interfaces;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface CourseInterface
{
    public function index(Request $request): JsonResponse;

    public function store(StoreCourseRequest $request): JsonResponse;

    public function update(UpdateCourseRequest $request): JsonResponse;

    public function delete(string $uuid): JsonResponse;

    public function deleteVideo(string $uuid, string $slug): JsonResponse;

    public function updateVideo(string $uuid, string $slug): JsonResponse;

    public function deleteFile(string $uuid, string $slug): JsonResponse;
}
