<?php

namespace App\Repositories;

use App\Interfaces\AuthInterface;
use Illuminate\Http\JsonResponse;
use App\Helpers\UploadFiles;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthInterface
{
    public function login(LoginRequest $request) : JsonResponse
    {
        $user = User::where('email', $request->input('email'))->firstOrFail();

        if (! Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user->tokens()->delete();
        $bearerToken = $user->createToken($user)->plainTextToken;


        return response()->json(
            [
                'message' => 'User logged in successfully',
                'access_token' => $bearerToken,
                'user' => $user,
            ], 200);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully'], 200);
    }

    public function register(RegisterRequest $request)  : JsonResponse
    {
         $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
        ]);

        $bearerToken = $user->createToken($user)->plainTextToken;

        return response()->json(['message' => 'User registered successfully', 'access_token' => $bearerToken], 201);
    }

    public function updateUserProfile(UpdateProfileRequest $request) : JsonResponse
    {
        $user = Auth::user()->profile;

        $image = $request->hasFile('profile_picture')
            ? (new UploadFiles)->UploadFiles('user_profile', $request->file('profile_picture'))
            : null;

        $user->update([
            'user_id' => Auth::id(),
            'filename' => $image['filename'] ?? null,
            'profile_picture' => $image['path'] ?? null,
            'bio',
            'credentials',
        ]);

        return response()->json(['message' => 'User profile updated successfully', 'user' => $user], 200);
    }
}