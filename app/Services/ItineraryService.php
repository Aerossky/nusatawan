<?php

namespace App\Services;

use App\Models\Itinerary;
use App\Models\ItineraryDestination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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


    public function getItinerary($id)
    {
        return Itinerary::with('itineraryDestinations.destination')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
    }

    public function createItinerary(array $data)
    {
        $data['user_id'] = Auth::id();

        // Membuat itinerary baru
        $itinerary = Itinerary::create($data);

        return $itinerary;
    }

    public function addDestinationToItinerary(array $data)
    {
        DB::beginTransaction();

        Log::info('Debug service data:', $data);

        try {
            // Find the itinerary
            $itinerary = Itinerary::findOrFail($data['itinerary_id']);

            // Determine if we need to create a new destination or use an existing one
            $destinationId = $data['destination_id'] ?? null;

            // Get the next order index if not provided
            if (!isset($data['order_index'])) {
                $maxOrder = ItineraryDestination::where('itinerary_id', $itinerary->id)
                    ->max('order_index') ?? 0;
                $orderIndex = $maxOrder + 1;
            } else {
                $orderIndex = $data['order_index'];
            }
            // Create the itinerary destination link
            $itineraryDestination = ItineraryDestination::create([
                'itinerary_id' => $itinerary->id,
                'destination_id' => $destinationId,
                'visit_date_time' => $data['visit_date_time'] ?? null,
                'order_index' => $orderIndex,
                'note' => $data['note'] ?? null,
            ]);


            DB::commit();

            return [
                'itinerary_destination_id' => $itineraryDestination->id,
                'destination_id' => $destinationId,
                'order_index' => 1
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add destination to itinerary: ' . $e->getMessage());
            throw $e;
        }
    }

    public function removeDestinationFromItinerary(array $data)
    {
        DB::beginTransaction();

        try {
            // Find the itinerary destination
            $itineraryDestination = ItineraryDestination::where('itinerary_id', $data['itinerary_id'])
                ->where('id', $data['destination_id'])  // Changed from destination_id to id
                ->firstOrFail();

            Log::info('Debug service data:', ['itinerary_destination' => $itineraryDestination]);

            // Delete the itinerary destination
            $itineraryDestination->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Destinasi berhasil dihapus dari rencana perjalanan'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to remove destination from itinerary: ' . $e->getMessage());
            throw $e;
        }
    }
}
