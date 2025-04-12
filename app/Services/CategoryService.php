<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class CategoryService
{
    /**
     * Mendapatkan daftar kategori dengan filter dan pagination opsional.
     *
     * @param  array  $filters  Filter yang dapat berisi:
     *                          - search: pencarian berdasarkan nama kategori
     *                          - per_page: jumlah data per halaman (default: 10)
     *
     * @return LengthAwarePaginator|Collection
     */
    public function getCategoriesList(array $filters = [])
    {
        $query = Category::withCount('destinations');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        $perPage = $filters['per_page'] ?? 10;

        return isset($filters['per_page']) ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Mendapatkan detail kategori tunggal dengan jumlah destinasi.
     *
     * @param  Category  $category  Objek kategori
     * @return Category  Kategori dengan hitungan destinasi
     */
    public function getCategory(Category $category)
    {
        return Category::withCount('destinations')->findOrFail($category->id);
    }

    /**
     * Membuat kategori baru di dalam database.
     *
     * @param  array  $data  Data kategori yang akan disimpan
     * @return Category  Kategori yang berhasil dibuat
     */
    public function createCategory(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Category::create($data);
        });
    }

    /**
     * Memperbarui kategori yang sudah ada.
     *
     * @param  Category  $category  Objek kategori yang akan diperbarui
     * @param  array  $data  Data baru untuk kategori
     * @return Category  Kategori yang telah diperbarui
     */
    public function updateCategory(Category $category, array $data)
    {
        return DB::transaction(function () use ($category, $data) {
            $category->update($data);
            return $category->fresh();
        });
    }

    /**
     * Menghapus kategori jika tidak digunakan oleh destinasi manapun.
     *
     * @param  Category  $category  Objek kategori yang akan dihapus
     * @throws Exception  Jika kategori masih digunakan oleh destinasi
     * @return bool  Status keberhasilan penghapusan
     */
    public function deleteCategory(Category $category)
    {
        return DB::transaction(function () use ($category) {
            if ($category->destinations()->exists()) {
                throw new Exception('Kategori tidak bisa dihapus karena masih digunakan oleh destinasi.');
            }

            return $category->delete();
        });
    }
}
