<?php

namespace App\Services;

use App\Models\Destination;
use App\Models\DestinationImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

class DestinationService
{
    private const DEFAULT_PER_PAGE = 10;
    private const MAX_IMAGES = 5;
    private const DEFAULT_SORT = 'created_at';
    private const DEFAULT_SORT_DIRECTION = 'desc';

    /**
     * Mendapatkan daftar destinasi dengan filter dan pencarian
     *
     * @param  array  $filters  Filter yang dapat berisi:
     *                          - search: pencarian berdasarkan nama tempat, kota, atau deskripsi
     *                          - category_id: filter berdasarkan kategori destinasi
     *                          - sort_by: pengurutan berdasarkan kriteria yang dipilih (rating_asc, rating_desc, newest, dll)
     *                          - per_page: jumlah data per halaman (default: 10)
     *
     * @return LengthAwarePaginator
     */
    public function getDestinationsList(array $filters = []): LengthAwarePaginator
    {
        $query = $this->buildBaseQuery();

        $this->applySearchFilter($query, $filters);
        $this->applyCategoryFilter($query, $filters);
        $this->applySorting($query, $filters);

        return $query->paginate($filters['per_page'] ?? self::DEFAULT_PER_PAGE);
    }

    /**
     * Menyimpan destinasi baru ke dalam database
     *
     * @param  array  $data  Data destinasi yang akan disimpan
     * @return Destination
     */
    public function createDestination(array $data): Destination
    {
        $destination = Destination::create([
            'place_name' => $data['place_name'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
            'created_by' => 1, // 'created_by' => auth()->id(), // TODO: Replace with appropriate user ID
            'administrative_area' => $data['administrative_area'],
            'province' => $data['province'],
            'rating' => 0,
            'rating_count' => 0,
            'time_minutes' => $data['time_minutes'],
            'best_visit_time' => $data['best_visit_time'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);

        $this->processImages($destination, $data);

        return $destination;
    }

    /**
     * Mengambil detail destinasi berdasarkan ID
     *
     * @param  Destination  $destination
     * @return Destination
     */
    public function getDestinationDetails(Destination $destination): Destination
    {
        return $destination->load(['category', 'images']);
    }

    /**
     * Menghapus destinasi berdasarkan ID
     *
     * @param  Destination  $destination
     * @return bool
     */
    public function deleteDestination(Destination $destination): bool
    {
        // Hapus semua gambar terkait destinasi
        foreach ($destination->images as $image) {
            Storage::delete($image->url);
            $image->delete();
        }

        // Hapus destinasi itu sendiri
        return $destination->delete();
    }

    /**
     * Menghapus gambar dari destinasi
     *
     * @param Destination $destination
     * @param DestinationImage $image
     * @return bool
     */
    public function deleteImage(Destination $destination, DestinationImage $image): bool
    {
        $this->validateImageOwnership($destination, $image);

        if ($this->isLastImage($destination)) {
            return false;
        }

        $this->handlePrimaryImageDeletion($destination, $image);

        Storage::delete($image->url);
        return $image->delete();
    }

    /**
     * Memperbarui data destinasi
     *
     * @param Destination $destination
     * @param array $data
     * @return Destination
     * @throws Exception
     */
    public function updateDestination(Destination $destination, array $data): Destination
    {
        $this->validateImageCount($destination, $data);

        $destination->update([
            'place_name' => $data['place_name'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
            'administrative_area' => $data['administrative_area'],
            'province' => $data['province'],
            'time_minutes' => $data['time_minutes'],
            'best_visit_time' => $data['best_visit_time'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);

        $this->processImages($destination, $data, false);
        $this->updatePrimaryImage($destination, $data);

        return $destination;
    }



    /**
     * Membuat query dasar untuk destinasi
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildBaseQuery()
    {
        return Destination::query()
            ->with(['category'])
            ->withCount(['reviews']);
    }

    /**
     * Menerapkan filter pencarian pada query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     */
    private function applySearchFilter($query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('place_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('city', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }
    }

    /**
     * Menerapkan filter kategori pada query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     */
    private function applyCategoryFilter($query, array $filters): void
    {
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
    }

    /**
     * Menerapkan pengurutan pada query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     */
    private function applySorting($query, array $filters): void
    {
        $column = self::DEFAULT_SORT;
        $direction = self::DEFAULT_SORT_DIRECTION;

        if (!empty($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'rating_asc':
                    $column = 'rating';
                    $direction = 'asc';
                    break;
                case 'rating_desc':
                    $column = 'rating';
                    $direction = 'desc';
                    break;
                case 'newest':
                    $column = 'created_at';
                    $direction = 'desc';
                    break;
            }
        }

        $query->orderBy($column, $direction);
    }

    /**
     * Memproses gambar untuk destinasi
     *
     * @param Destination $destination
     * @param array $data
     * @param bool $makePrimary
     * @return void
     */
    private function processImages(Destination $destination, array $data, bool $makePrimary = true): void
    {
        if (isset($data['image']) && is_array($data['image'])) {
            $this->handleImageUploads(
                $destination,
                $data['image'],
                $data['primary_image_index'] ?? 0,
                $makePrimary
            );
        }
    }

    /**
     * Validasi kepemilikan gambar
     *
     * @param Destination $destination
     * @param DestinationImage $image
     * @return void
     */
    private function validateImageOwnership(Destination $destination, DestinationImage $image): void
    {
        if ($image->destination_id !== $destination->id) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }

    /**
     * Cek apakah gambar adalah yang terakhir
     *
     * @param Destination $destination
     * @return bool
     */
    private function isLastImage(Destination $destination): bool
    {
        return $destination->images()->count() <= 1;
    }

    /**
     * Mengatur gambar utama baru saat gambar utama dihapus
     *
     * @param Destination $destination
     * @param DestinationImage $image
     * @return void
     */
    private function handlePrimaryImageDeletion(Destination $destination, DestinationImage $image): void
    {
        if ($image->is_primary) {
            $nextImage = DestinationImage::where('destination_id', $destination->id)
                ->where('id', '!=', $image->id)
                ->first();

            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }
    }

    /**
     * Validasi jumlah gambar
     *
     * @param Destination $destination
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validateImageCount(Destination $destination, array $data): void
    {
        $existingImageCount = $destination->images()->count();
        $newImageCount = isset($data['image']) ? count($data['image']) : 0;

        if (($existingImageCount + $newImageCount) > self::MAX_IMAGES) {
            throw new Exception("Total gambar tidak boleh lebih dari " . self::MAX_IMAGES . ".");
        }
    }

    /**
     * Memperbarui gambar utama
     *
     * @param Destination $destination
     * @param array $data
     * @return void
     */
    private function updatePrimaryImage(Destination $destination, array $data): void
    {
        if (isset($data['primary_image_index'])) {
            DestinationImage::where('destination_id', $destination->id)
                ->where('id', '!=', $data['primary_image_index'])
                ->update(['is_primary' => false]);

            $primaryImage = $destination->images()->find($data['primary_image_index']);
            if ($primaryImage) {
                $primaryImage->update(['is_primary' => true]);
            }
        }
    }

    /**
     * Menangani upload gambar
     *
     * @param Destination $destination Destinasi yang akan diunggah gambarnya
     * @param array $images Array file gambar
     * @param int|null $primaryIndex Indeks gambar yang akan dijadikan utama
     * @param bool $makePrimary Apakah perlu mengatur gambar utama
     * @return void
     */
    private function handleImageUploads(Destination $destination, array $images, ?int $primaryIndex = 0, bool $makePrimary = true): void
    {
        foreach ($images as $index => $imageFile) {
            $imageName = uniqid() . '-' . Str::random(10) . '-' . time() . '.' . $imageFile->extension();
            $path = $imageFile->storeAs('destinations', $imageName);
            $url = str_replace('', '', $path);

            $isPrimary = $makePrimary && $primaryIndex !== null && $index == $primaryIndex;

            if ($isPrimary) {
                DestinationImage::where('destination_id', $destination->id)
                    ->update(['is_primary' => false]);
            }

            DestinationImage::create([
                'destination_id' => $destination->id,
                'url' => $url,
                'is_primary' => $isPrimary,
            ]);
        }
    }
}
