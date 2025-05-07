<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
     * @return array
     * @throws ValidationException
     */
    // public function login(array $credentials): array
    // {
    //     if (!Auth::attempt($credentials)) {
    //         throw ValidationException::withMessages([
    //             'email' => ['Email atau password salah.'],
    //         ]);
    //     }

    //     $user = Auth::user();

    //     // Periksa status user
    //     if ($user->status !== 'active') {
    //         Auth::logout();
    //         throw ValidationException::withMessages([
    //             'email' => ['Akun Anda tidak aktif. Silahkan hubungi admin.'],
    //         ]);
    //     }

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return [
    //         'user' => $user,
    //         'token' => $token
    //     ];
    // }

    /**
     * Logout current user
     *
     * @return bool
     */
    // public function logout(): bool
    // {
    //     // Menghapus token saat ini
    //     Auth::user()->currentAccessToken()->delete();

    //     return true;
    // }


    private function uploadImage($image)
    {
        // Generate nama gambar unik & random
        $imageName = uniqid() . '-' . Str::random(10) . '-' . time() . '.' . $image->extension();
        $path = $image->storeAs('users', $imageName);

        // Return path yang dapat diakses oleh public
        return str_replace('', '', $path);
    }
}
