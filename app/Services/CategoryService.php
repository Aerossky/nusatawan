<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    /**
     * Get list of categories with optional filtering and pagination.
     *
     * @param  array  $filters  Filter yang dapat berisi:
     *                          - search: pencarian berdasarkan nama kategori
     *                          - per_page: jumlah data per halaman (default: 10)
     *
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesList(array $filters = [])
    {
        $query = Category::withCount('destinations');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['per_page'])) {
            return $query->paginate($filters['per_page']);
        }

        return $query->get();
    }
}
