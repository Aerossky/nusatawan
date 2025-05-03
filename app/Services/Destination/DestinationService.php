<?php

namespace App\Services\Destination;

use App\Models\Destination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DestinationService
{
    private const DEFAULT_PER_PAGE = 10;

    protected $queryService;
    protected $imageService;
    protected $geoService;

    public function __construct(
        DestinationQueryService $queryService,
        DestinationImageService $imageService,
        DestinationGeoService $geoService
    ) {
        $this->queryService = $queryService;
        $this->imageService = $imageService;
        $this->geoService = $geoService;
    }

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
        $query = $this->queryService->buildBaseQuery();

        $this->queryService->applySearchFilter($query, $filters);
        $this->queryService->applyCategoryFilter($query, $filters);
        $this->queryService->applySorting($query, $filters);

        return $query->paginate($filters['per_page'] ?? self::DEFAULT_PER_PAGE);
    }

    /**
     * Mendapatkan daftar destinasi terdekat berdasarkan koordinat
     *
     * @param  array  $filters  Filter yang dapat berisi:
     *                          - lat: latitude dari lokasi saat ini
     *                          - lng: longitude dari lokasi saat ini
     *                          - exclude_id: ID destinasi yang harus dikecualikan
     *                          - max_distance: jarak maksimum dalam kilometer
     *                          - per_page: jumlah data per halaman (default: 10)
     *
     * @return LengthAwarePaginator
     */
    public function getNearbyDestinations(array $filters = []): LengthAwarePaginator
    {
        return $this->geoService->getNearbyDestinations($filters);
    }

    /**
     * Mengambil detail destinasi berdasarkan slug
     *
     * @param  string  $slug
     * @return Destination|null
     */
    public function getDestinationBySlug(string $slug): ?Destination
    {
        return $this->queryService->getDestinationBySlug($slug);
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
            'created_by' => Auth::id(), // Menggunakan Auth::id() untuk mendapatkan ID user yang terautentikasi
            'administrative_area' => $data['administrative_area'],
            'province' => $data['province'],
            'rating' => 0,
            'rating_count' => 0,
            'time_minutes' => $data['time_minutes'],
            'best_visit_time' => $data['best_visit_time'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);

        if (isset($data['image']) && is_array($data['image'])) {
            $this->imageService->processImages(
                $destination,
                $data['image'],
                $data['primary_image_index'] ?? 0
            );
        }

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
        $this->imageService->deleteDestinationImages($destination);

        // Hapus destinasi itu sendiri
        return $destination->delete();
    }

    /**
     * Memperbarui data destinasi
     *
     * @param Destination $destination
     * @param array $data
     * @return Destination
     * @throws \Exception
     */
    public function updateDestination(Destination $destination, array $data): Destination
    {
        $this->imageService->validateImageCount($destination, $data);

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

        if (isset($data['image']) && is_array($data['image'])) {
            $this->imageService->processImages($destination, $data['image'], $data['primary_image_index'] ?? 0, false);
        }

        if (isset($data['primary_image_index'])) {
            $this->imageService->updatePrimaryImage($destination, $data['primary_image_index']);
        }

        return $destination;
    }

    /**
     * Mengambil total destinasi yang dibuat oleh pengguna
     *
     * @param int $userId
     * @return int
     */
    public function getTotalDestinationsByUser($userId): int
    {
        return Destination::where('created_by', $userId)->count();
    }
}
