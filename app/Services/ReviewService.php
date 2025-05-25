<?php

namespace App\Services;

use App\Models\Destination;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewService
{
    public function submitReview(int $destinationId, array $data)
    {
        // Periksa apakah Destination ada
        $destination = Destination::findOrFail($destinationId);

        if (!$destination) {
            throw new \Exception('Destination not found');
        }

        // Periksa apakah user sudah memberikan review sebelumnya
        $existingReview = Review::where('user_id', Auth::id())
            ->where('destination_id', $destinationId)
            ->first();

        if ($existingReview) {
            // Update existing review
            $existingReview->update([
                'rating' => $data['rating'] ?? 0,
                'comment' => $data['comment'] ?? null,
                'updated_at' => now(),
            ]);

            // Update destination average rating
            $this->updateDestinationRating($destinationId);

            return $existingReview;
        }

        // Buat review baru
        $review = Review::create([
            'user_id' => Auth::id(),
            'destination_id' => $destinationId,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
        // Update destination average rating
        $this->updateDestinationRating($destinationId);

        return $review;
    }

    public function getReviewsByDestinationId(int $destinationId, int $perPage = 10, string $order = 'desc')
    {
        return Review::where('destination_id', $destinationId)
            ->with('user')
            ->orderBy('created_at', $order)
            ->paginate($perPage);
    }

    /**
     * Update rata-rata rating untuk destinasi yang ditentukan.
     *
     * Dijalankan setiap kali review dibuat atau diupdate.
     *
     * @param int $destinationId ID destinasi
     */
    private function updateDestinationRating(int $destinationId): void
    {
        $avgRating = $this->getDestinationRating($destinationId);

        Destination::where('id', $destinationId)->update([
            'rating' => $avgRating,
            'updated_at' => now(),
        ]);
    }

    /**
     * Hapus review yang ditentukan.
     *
     * Setelah hapus, rata-rata rating untuk destinasi yang terkait juga akan diupdate.
     *
     * @param Review $review Review yang akan dihapus
     */
    public function destroyReview(Destination $destination, Review $review)
    {
        $review->delete();

        // Update destination average rating
        $this->updateDestinationRating($destination->id);
    }

    /**
     * Mengambil review yang dibuat oleh user untuk destinasi tertentu.
     *
     * Jika user belum memberikan review untuk destinasi ini, maka akan dikembalikan null.
     *
     * @param int $destinationId ID destinasi
     * @return Review|null
     */
    public function getUserReview(int $destinationId)
    {
        // Make sure user is authenticated
        if (!Auth::check()) {
            return null;
        }

        return Review::where('user_id', Auth::id())
            ->where('destination_id', $destinationId)
            ->first();
    }

    /**
     * Menghitung rata-rata rating untuk destinasi yang ditentukan.
     *
     * @param int $destinationId ID destinasi
     * @return float Rata-rata rating (antara 0 dan 5)
     */
    public function getDestinationRating(int $destinationId): float
    {
        return Destination::where('id', $destinationId)
            ->avg('rating') ?? 0;
    }

    /**
     * Menghitung jumlah review untuk destinasi yang ditentukan.
     *
     * @param int $destinationId ID destinasi
     * @return int Jumlah review
     */
    public function getDestinationReviewCount(int $destinationId): int
    {
        return Review::where('destination_id', $destinationId)
            ->count();
    }
}
