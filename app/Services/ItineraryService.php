<?php

namespace App\Services;

use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;

class ItineraryService
{
    public function getAllItineraries(array $filters = [])
    {
        $query = Itinerary::where('user_id', Auth::id())
            ->withCount('itineraryDestinations');

        // Apply search filter
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        // Apply status filter
        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply sorting
        if (isset($filters['sort'])) {
            switch ($filters['sort']) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate(9);
    }
    public function createItinerary(array $data)
    {
        $data['user_id'] = Auth::id();

        return Itinerary::create($data);
    }
}
