<?php

namespace App\Http\Controllers;

use App\Helpers\UploadImage;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->input('email'))->with('roles')->firstOrFail();

        if (! Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user->tokens()->delete();
        $bearerToken = $user->createToken($user)->plainTextToken;

        // Get user roles
        $roles = $user->roles->pluck('slug')->toArray();

        return response()->json(
            [
                'message' => 'User logged in successfully',
                'access_token' => $bearerToken,
                'user' => $user,
                'roles' => $roles,
            ], 200);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully'], 200);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
        ]);

        // Assign default student role
        $user->assignRole('student');

        $bearerToken = $user->createToken($user)->plainTextToken;

        // Load roles for response
        $user->load('roles');
        $roles = $user->roles->pluck('slug')->toArray();

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $bearerToken,
            'user' => $user,
            'roles' => $roles
        ], 201);
    }

    public function updateUserProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user()->profile;

        $image = $request->hasFile('profile_picture')
            ? (new UploadImage)->uploadImage('user_profile', $request->file('profile_picture'))
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
