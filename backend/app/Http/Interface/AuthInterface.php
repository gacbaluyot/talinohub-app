<?php

namespace App\Interfaces;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;

interface AuthInterface
{
    public function login(LoginRequest $request): JsonResponse;

    public function logout(): JsonResponse;

    public function register(RegisterRequest $request): JsonResponse;

    public function updateUserProfile(UpdateProfileRequest $request): JsonResponse;
}
