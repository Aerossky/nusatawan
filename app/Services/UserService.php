<?php

namespace App\Services;

use App\Models\User;

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
}
