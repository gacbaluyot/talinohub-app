<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Repositories\AuthRepository;

class AuthController extends Controller
{
    private AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(LoginRequest $request)
    {
        return $this->authRepository->login($request);
    }

    public function logout()
    {
        return $this->authRepository->logout();
    }

    public function register(RegisterRequest $request)
    {
        return $this->authRepository->register($request);
    }

    public function updateUserProfile(UpdateProfileRequest $request)
    {
        return $this->authRepository->updateUserProfile($request);
    }
}