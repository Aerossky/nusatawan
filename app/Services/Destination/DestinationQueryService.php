<?php

namespace App\Services\Destination;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DestinationQueryService
{
    private const DEFAULT_SORT = 'created_at';
    private const DEFAULT_SORT_DIRECTION = 'desc';

    /**
     * Membuat query dasar untuk destinasi
     *
     * @return Builder
     */
    public function buildBaseQuery(): Builder
    {
        $query = Destination::query()
            ->with(['category', 'primaryImage'])
            ->withCount(['likedByUsers as likes_count', 'reviews']);

        if (Auth::check()) {
            $userId = Auth::id();

            $query->withExists([
                'likedByUsers as is_liked_by_user' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ]);
        }

        return $query;
    }

    /**
     * Menerapkan filter pencarian pada query
     *
     * @param Builder $query
     * @param array $filters
     * @return void
     */
    public function applySearchFilter(Builder $query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('place_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('administrative_area', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('province', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }
    }

    /**
     * Menerapkan filter kategori pada query
     *
     * @param Builder $query
     * @param array $filters
     * @return void
     */
    public function applyCategoryFilter(Builder $query, array $filters): void
    {
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
    }

    /**
     * Menerapkan pengurutan pada query
     *
     * @param Builder $query
     * @param array $filters
     * @return void
     */
    public function applySorting(Builder $query, array $filters): void
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
                case 'likes_desc':
                    $column = 'likes_count';
                    $direction = 'desc';
                    break;
                case 'likes_asc':
                    $column = 'likes_count';
                    $direction = 'asc';
                    break;
            }
        }

        $query->orderBy($column, $direction);
    }

    /**
     * Mengambil detail destinasi berdasarkan slug
     *
     * @param string $slug
     * @return Destination|null
     */
    public function getDestinationBySlug(string $slug): ?Destination
    {
        $query = Destination::where('slug', $slug)
            ->withCount(['likedByUsers as likes_count', 'reviews', 'primaryImage', 'images']);

        if (Auth::check()) {
            $userId = Auth::id();

            $query->withExists([
                'likedByUsers as is_liked_by_user' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ]);
        }

        $destination = $query->first();

        if (!$destination) {
            return null;
        }

        return $destination->load(['category', 'images']);
    }
}
