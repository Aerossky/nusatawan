<?php

namespace App\Services;

use App\Models\Destination;

class DestinationService
{


    /**
     * Mendapatkan daftar destinasi dengan filter dan pencarian
     *
     * @param  array  $filters  Filter yang dapat berisi:
     *                          - search: pencarian berdasarkan nama tempat, kota, atau deskripsi
     *                          - category_id: filter berdasarkan kategori destinasi
     *                          - sort_by: pengurutan berdasarkan kriteria yang dipilih (rating_asc, rating_desc, newest, dll)
     *                          - per_page: jumlah data per halaman (default: 10)
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDestinationsList(array $filters = [])
    {
        $query = Destination::query()
            ->with(['category']) // Memuat relasi kategori pada setiap destinasi
            ->withCount(['reviews']); // Menghitung jumlah ulasan (review) untuk setiap destinasi

        // Pencarian berdasarkan nama tempat, kota, atau deskripsi
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('place_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('city', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Filter berdasarkan kategori destinasi
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Pengurutan berdasarkan kriteria yang dipilih
        if (!empty($filters['sort_by'])) {
            // Nilai sort_by dapat berupa: rating_asc, rating_desc, newest, dll
            switch ($filters['sort_by']) {
                case 'rating_asc':
                    $query->orderBy('rating', 'asc'); // Urutkan berdasarkan rating terendah
                    break;

                case 'rating_desc':
                    $query->orderBy('rating', 'desc'); // Urutkan berdasarkan rating tertinggi
                    break;

                case 'newest':
                    $query->orderBy('created_at', 'desc'); // Urutkan berdasarkan data terbaru
                    break;

                default:
                    $query->orderBy('created_at', 'desc'); // Default: data terbaru
                    break;
            }
        } else {
            // Jika tidak ada sort_by, urutkan berdasarkan data terbaru
            $query->orderBy('created_at', 'desc');
        }

        // Kembalikan hasil dengan pagination (default 10 data per halaman)
        return $query->paginate($filters['per_page'] ?? 10);
    }
}
