<?php

namespace App\Services;

use App\Models\LikedDestination;

class LikeService
{

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
