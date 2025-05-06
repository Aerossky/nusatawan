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
        // Get coordinates from filters
        $lat = $filters['lat'] ?? null;
        $lng = $filters['lng'] ?? null;
        $excludeId = $filters['exclude_id'] ?? null;
        $perPage = $filters['per_page'] ?? self::DEFAULT_PER_PAGE;

        // Create base query
        $baseQuery = $this->queryService->buildBaseQuery();

        // Exclude destination if specified
        if ($excludeId) {
            $baseQuery->where('id', '!=', $excludeId);
        }

        // Eager load images
        $baseQuery->with('images');

        // Create subquery for distance calculation
        $subQuery = $baseQuery->toSql();

        // Create new query with distance calculation using Haversine formula
        $query = DB::table(DB::raw("({$subQuery}) as base_destinations"))
            ->mergeBindings($baseQuery->getQuery())
            ->selectRaw("base_destinations.*, (
                6371 * acos(
                    cos(radians($lat)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians($lng)) +
                    sin(radians($lat)) *
                    sin(radians(latitude))
                )
            ) AS distance");

        // Add distance constraint if specified
        if (isset($filters['max_distance'])) {
            $maxDistance = $filters['max_distance'];
            $query->havingRaw("distance <= ?", [$maxDistance]);
        }

        // Order by distance
        $query->orderBy('distance', 'asc');

        // Paginate results
        $paginator = $query->paginate($perPage);

        // Process results to include relationships
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
    private function processNearbyResults(LengthAwarePaginator $paginator): void
    {
        if ($paginator->count() > 0) {
            // Ambil ID dari hasil paginasi
            $destinationIds = collect($paginator->items())->pluck('id')->toArray();

            // Load destinasi dengan eager loading images
            $destinationsWithImages = Destination::with('images')
                ->whereIn('id', $destinationIds)
                ->get()
                ->keyBy('id');

            // Ganti item dalam paginasi dengan model yang memiliki relasi
            $paginator->setCollection(collect($paginator->items())->map(function ($item) use ($destinationsWithImages) {
                if (isset($destinationsWithImages[$item->id])) {
                    // Tambahkan properti distance ke model
                    $destinationsWithImages[$item->id]->distance = $item->distance;
                    return $destinationsWithImages[$item->id];
                }
                return $item;
            }));
        }
    }
}
