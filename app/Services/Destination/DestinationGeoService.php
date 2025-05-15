<?php

namespace App\Services\Destination;

use App\Models\Destination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DestinationGeoService
{
    private const DEFAULT_PER_PAGE = 10;
    private $queryService;

    public function __construct(DestinationQueryService $queryService)
    {
        $this->queryService = $queryService;
    }

    /**
     * Mendapatkan daftar destinasi terdekat berdasarkan koordinat yang diberikan,
     * dengan opsi untuk mengecualikan destinasi tertentu dan membatasi jarak maksimum.
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
        // Log input filters untuk debugging
        Log::info('Input filters:', $filters);

        // Ambil nilai dari filters
        $lat = $filters['lat'] ?? null;
        $lng = $filters['lng'] ?? null;
        $excludeId = $filters['exclude_id'] ?? null;
        $perPage = $filters['per_page'] ?? self::DEFAULT_PER_PAGE;
        $categoryId = $filters['category_id'] ?? null;
        $sortBy = $filters['sort_by'] ?? null;
        $sort = $filters['sort'] ?? 'distance';
        $maxDistance = $filters['max_distance'] ?? null;

        // Log sort parameters untuk debugging
        Log::info('Sort parameters:', [
            'sortBy' => $sortBy,
            'sort' => $sort
        ]);

        // Mulai base query
        $baseQuery = $this->queryService->buildBaseQuery();

        // Filter: Exclude ID
        if ($excludeId) {
            $baseQuery->where('id', '!=', $excludeId);
        }

        // Filter: category_id
        if ($categoryId) {
            $baseQuery->where('category_id', $categoryId);
        }


        // Eager load relasi
        $baseQuery->with('images');

        // Convert baseQuery ke subQuery SQL
        $subQuery = $baseQuery->toSql();

        // Hitung jarak pakai Haversine formula
        $query = DB::table(DB::raw("({$subQuery}) as base_destinations"))
            ->mergeBindings($baseQuery->getQuery())
            ->selectRaw("base_destinations.*, (
                6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )
            ) AS distance", [$lat, $lng, $lat]);

        // Filter: Jarak maksimum
        if ($maxDistance) {
            $query->havingRaw("distance <= ?", [$maxDistance]);
        }

        // Menerapkan pengurutan berdasarkan sort_by dengan pattern if-elseif-else
        // untuk menghindari conflict dalam pengurutan
        if ($sortBy === 'newest') {
            Log::info('Applied sorting: newest');
            $query->orderByDesc('created_at');
        } elseif ($sortBy === 'oldest') {
            Log::info('Applied sorting: oldest');
            $query->orderBy('created_at');
        } elseif ($sortBy === 'rating_desc') {
            Log::info('Applied sorting: rating_desc');
            $query->orderByDesc('rating');
        } elseif ($sortBy === 'rating_asc') {
            Log::info('Applied sorting: rating_asc');
            $query->orderBy('rating');
        } elseif ($sortBy === 'likes_desc') {
            Log::info('Applied sorting: likes_desc');
            $query->orderByDesc('likes_count');
        } elseif ($sortBy === 'likes_asc') {
            Log::info('Applied sorting: likes_asc');
            $query->orderBy('likes_count');
        } elseif ($sortBy === 'a_to_z') {
            Log::info('Applied sorting: a_to_z');
            $query->orderBy('name');
        } elseif ($sortBy === 'z_to_a') {
            Log::info('Applied sorting: z_to_a');
            $query->orderByDesc('name');
        } else {
            // Default: sort by distance
            Log::info('Applied default sorting: distance');
            $query->orderBy('distance');
        }

        // Secondary sort for consistency when primary sorts have same values
        if ($sortBy !== null && $sortBy !== 'distance') {
            $query->orderBy('distance'); // Secondary sort by distance
        }

        // Paginate
        $paginator = $query->paginate($perPage);

        // Log hasil paginator untuk debugging
        Log::info('Paginator counts:', [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
        ]);

        // Tambahkan relasi (images) ke hasil
        $this->processNearbyResults($paginator);

        return $paginator;
    }

    /**
     * Mendapatkan daftar destinasi dalam radius tertentu berdasarkan koordinat yang diberikan.
     *
     * @param float $lat Latitude dari lokasi saat ini
     * @param float $lng Longitude dari lokasi saat ini
     * @param float $radiusKm Radius pencarian dalam kilometer (default: 50)
     * @return Collection
     */
    public function getNearbyDestinationRaws(float $lat, float $lng, float $radiusKm = 50)
    {
        return Destination::select('*')
            ->whereRaw("(6371 * acos(cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(latitude)))) < ?", [$radiusKm])
            ->with('images')
            ->get();
    }

    /**
     * Memproses hasil query untuk menyertakan relasi
     *
     * @param LengthAwarePaginator $paginator
     * @return void
     */

    /**
     * Process results and add related entities
     */
    protected function processNearbyResults(LengthAwarePaginator $paginator)
    {
        // Kita perlu mengonversi item-item dari stdClass ke model Destination
        $items = $paginator->items();
        $destinationIds = array_column($items, 'id');

        // Ambil destinasi dengan eager loading
        $destinations = \App\Models\Destination::with(['images', 'category'])
            ->whereIn('id', $destinationIds)
            ->get()
            ->keyBy('id');

        // Gunakan collection baru untuk menyimpan hasil yang sudah diproses
        $processedItems = [];

        foreach ($items as $item) {
            if (isset($destinations[$item->id])) {
                $destination = $destinations[$item->id];
                // Tambahkan jarak ke model
                $destination->distance = $item->distance;
                $processedItems[] = $destination;
            }
        }

        // Ganti items di paginator dengan model yang sudah diproses
        $paginator->setCollection(collect($processedItems));
    }
}
