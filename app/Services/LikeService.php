<?php

namespace App\Services;

use App\Models\LikedDestination;

class LikeService
{
    /**
     * Mengambil daftar destinasi yang disukai oleh pengguna tertentu.
     *
     * @param int $userId
     * @return array
     */
    public function getLikedDestinations(int $userId, int $perPage = 9)
    {
        $likedDestinations = LikedDestination::where('user_id', $userId)
            ->with(['destination.images', 'destination.likedByUsers']) // Mengambil relasi likedByUsers untuk menghitung likes_count
            ->paginate($perPage);

        // Tambahkan field is_liked_by_user = true dan likes_count untuk setiap destination
        $likedDestinations->getCollection()->transform(function ($likedDestination) {
            if ($likedDestination->destination) {
                $destination = $likedDestination->destination;
                $destination->is_liked_by_user = true; // Set is_liked_by_user menjadi true

                // Hitung jumlah likes dari relasi likedByUsers
                $destination->likes_count = 0; // Hitung jumlah likes
            }
            return $likedDestination;
        });

        return $likedDestinations;
    }


    /**
     * Menyukai destinasi tertentu.
     *
     * @param int $userId
     * @param int $destinationId
     * @return LikedDestination|null
     */
    public function likeDestination(int $userId, int $destinationId): ?LikedDestination
    {
        if ($this->isDestinationLiked($userId, $destinationId)) {
            // Sudah like, tidak perlu buat lagi
            return null;
        }

        return LikedDestination::create([
            'user_id' => $userId,
            'destination_id' => $destinationId,
        ]);
    }


    /**
     * Menghapus like dari destinasi tertentu.
     *
     * @param int $userId
     * @param int $destinationId
     * @return bool
     */
    public function unlikeDestination(int $userId, int $destinationId): bool
    {
        return LikedDestination::where('user_id', $userId)
            ->where('destination_id', $destinationId)
            ->delete() > 0;
    }
    /**
     * Memeriksa apakah pengguna menyukai destinasi tertentu.
     *
     * @param int $userId
     * @param int $destinationId
     * @return bool
     */
    public function isDestinationLiked(int $userId, int $destinationId): bool
    {
        return LikedDestination::where('user_id', $userId)
            ->where('destination_id', $destinationId)
            ->exists();
    }


    /**
     * Mengambil total destinasi yang disukai oleh pengguna tertentu.
     *
     * @param int $userId
     * @return int
     */
    public function getTotalLikesByUser(int $userId): int
    {
        return LikedDestination::where('user_id', $userId)->count();
    }

    /**
     * Mengambil total like dari destinasi tertentu.
     *
     * @param int $userId
     * @return int
     */
    public function getTotalLikesByDestination(int $destinationId): int
    {
        return LikedDestination::where('destination_id', $destinationId)->count();
    }
}
