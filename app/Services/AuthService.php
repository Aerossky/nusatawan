<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register a new user
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        $imagePath = null;

        if (!empty($data['image'])) {
            $imagePath = $this->uploadImage($data['image']);
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'active',
            'isAdmin' => false,
            'image' => $imagePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Authenticate a user
     *
     * @param array $credentials
     * @param bool $remember
     * @return User
     * @throws ValidationException
     */
    public function login(array $credentials, bool $remember = false): User
    {
        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $user = Auth::user();

        // Periksa status user
        if ($user->status !== 'active') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Akun Anda tidak aktif. Silahkan hubungi admin.'],
            ]);
        }

        return $user;
    }

    /**
     * Generate API token for a user (if using Sanctum)
     *
     * @param User $user
     * @return string|null
     */
    public function generateApiToken(User $user): ?string
    {
        // Check if user model has the HasApiTokens trait
        if (method_exists($user, 'createToken')) {
            return $user->createToken('auth_token')->plainTextToken;
        }

        return null;
    }

    /**
     * Logout current user
     *
     * @return bool
     */
    public function logout(): bool
    {
        // No need to explicitly check for currentAccessToken
        // Just perform regular logout
        Auth::logout();

        return true;
    }

    /**
     * Upload user profile image
     *
     * @param $image
     * @return string
     */
    private function uploadImage($image)
    {
        // Generate nama gambar unik & random
        $imageName = uniqid() . '-' . Str::random(10) . '-' . time() . '.' . $image->extension();
        $path = $image->storeAs('users', $imageName);

        // Return path yang dapat diakses oleh public
        return $path;
    }
}
