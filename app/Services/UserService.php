<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    /**
     * Mendapatkan daftar pengguna dengan filter dan pencarian
     */
    public function getUsersList(array $filters = [])
    {
        $query = User::query()
            ->withCount(['likedDestinations', 'reviews']);

        // Pencarian berdasarkan nama atau email
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Filter berdasarkan status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Urutkan berdasarkan tanggal bergabung secara default
        $query->orderBy('created_at', 'desc');

        // Ambil data pengguna dengan pagination
        return $query->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Mendapatkan detail pengguna beserta relasi
     */
    public function getUserDetails(User $user)
    {
        return $user->load([
            'reviews.destination',
            'itineraries'
        ]);
    }

    /**
     * Membuat pengguna baru
     */

    public function createUser(array $data)
    {
        // Handle image upload jika ada
        if (isset($data['image']) && $data['image']) {
            $imagePath = $this->uploadImage($data['image']);
            $data['image'] = $imagePath;
        }

        // Hash password
        $data['password'] = Hash::make($data['password']);

        // Set default status jika tidak diberikan
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }

        // Set default isAdmin jika tidak diberikan
        if (!isset($data['isAdmin'])) {
            $data['isAdmin'] = false;
        }

        // Buat user baru
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'image' => $data['image'] ?? null,
            'status' => $data['status'],
            'isAdmin' => $data['isAdmin']
        ]);
    }

    private function uploadImage($image)
    {
        // Generate nama gambar unik & random
        $imageName = uniqid() . '-' . Str::random(10) . '-' . time() . '.' . $image->extension();
        $path = $image->storeAs('public/users', $imageName);

        // Return path yang dapat diakses oleh public
        return str_replace('public/', 'storage/', $path);
    }
}
