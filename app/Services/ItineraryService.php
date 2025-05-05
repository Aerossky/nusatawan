<?php

namespace App\Services;

use App\Models\Itinerary;
use App\Models\ItineraryDestination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Class ItineraryService
 *
 * Service for managing travel itineraries and their destinations.
 * Provides CRUD functions and other operations related to itineraries.
 *
 * @package App\Services
 */
class ItineraryService
{
    /**
     * Get all itineraries belonging to the currently logged-in user with various filters and sorting options
     *
     * @param array $filters Array of filters with possible keys:
     *                      - 'search': Search by title
     *                      - 'status': Filter by status
     *                      - 'sort': Sorting method ('oldest', 'title_asc', 'title_desc', or default newest to oldest)
     * @return \Illuminate\Pagination\LengthAwarePaginator Paginated itinerary objects with destination counts
     */
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

    /**
     * Get a specific itinerary with its destinations
     *
     * @param int $id The itinerary ID
     * @return \App\Models\Itinerary Itinerary with loaded destination relationships
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If itinerary not found or doesn't belong to user
     */
    public function getItinerary($id)
    {
        return Itinerary::with('itineraryDestinations.destination')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
    }

    /**
     * Create a new itinerary for the currently logged-in user
     *
     * @param array $data Itinerary data
     * @return \App\Models\Itinerary The newly created itinerary
     */
    public function createItinerary(array $data)
    {
        $data['user_id'] = Auth::id();

        // Create new itinerary
        $itinerary = Itinerary::create($data);

        return $itinerary;
    }

    /**
     * Add a destination to an existing itinerary
     *
     * @param array $data Data containing:
     *                   - 'itinerary_id': ID of the itinerary
     *                   - 'destination_id': ID of the destination (optional)
     *                   - 'visit_date_time': Planned visit date/time (optional)
     *                   - 'order_index': Order in the itinerary (optional, will be calculated if not provided)
     *                   - 'note': Additional notes (optional)
     * @return array Details of the added destination
     * @throws \Exception If the operation fails
     */
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

    /**
     * Remove a destination from an itinerary
     *
     * @param array $data Data containing:
     *                   - 'itinerary_id': ID of the itinerary
     *                   - 'destination_id': ID of the itinerary destination to remove
     * @return array Operation status
     * @throws \Exception If the operation fails
     */
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

    /**
     * Update a destination within an itinerary
     *
     * @param array $data Data containing:
     *                   - 'id': ID of the itinerary destination
     *                   - 'itinerary_id': ID of the itinerary
     *                   - 'destination_id': ID of the destination (optional)
     *                   - 'visit_date_time': Planned visit date/time (optional)
     *                   - 'note': Additional notes (optional)
     * @return array Details of the updated destination
     * @throws \Exception If the operation fails
     */
    public function updateDestinationInItinerary(array $data)
    {
        DB::beginTransaction();

        try {
            // Find the itinerary destination
            $itineraryDestination = ItineraryDestination::where('id', $data['id'])
                ->where('itinerary_id', $data['itinerary_id'])
                ->firstOrFail();

            // Update the itinerary destination
            $itineraryDestination->update([
                'destination_id' => $data['destination_id'] ?? $itineraryDestination->destination_id,
                'visit_date_time' => $data['visit_date_time'] ?? $itineraryDestination->visit_date_time,
                'note' => $data['note'] ?? $itineraryDestination->note,
            ]);

            DB::commit();

            return [
                'itinerary_destination_id' => $itineraryDestination->id,
                'destination_id' => $itineraryDestination->destination_id,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update destination in itinerary: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get a specific destination by its ID
     *
     * @param int $itineraryDestinationId The ID of the itinerary destination
     * @return array|null Destination details or null if not found or not accessible
     */
    public function getDestinationById($itineraryDestinationId)
    {
        try {
            $itineraryDestination = ItineraryDestination::with(['destination'])
                ->where('id', $itineraryDestinationId)
                ->first();

            if (!$itineraryDestination) {
                return null;
            }

            // Check if user owns this itinerary
            if ($itineraryDestination->itinerary->user_id !== Auth::id()) {
                return null;
            }

            $result = [
                'id' => $itineraryDestination->id,
                'place_name' => $itineraryDestination->destination ? $itineraryDestination->destination->place_name : null,
                'administrative_area' => $itineraryDestination->destination ? $itineraryDestination->destination->administrative_area : null,
                'province' => $itineraryDestination->destination ? $itineraryDestination->destination->province : null,
                'visit_date_time' => $itineraryDestination->visit_date_time,
                'note' => $itineraryDestination->note,
            ];

            return $result;
        } catch (\Exception $e) {
            Log::error('Error getting destination by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update the visit time and note for a destination in an itinerary
     *
     * @param int $itineraryDestinationId The ID of the itinerary destination
     * @param int $itineraryId The ID of the itinerary
     * @param string|null $visitTime New visit time (optional)
     * @param string|null $note New note (optional)
     * @return bool True if the update was successful, false otherwise
     */
    public function updateDestination($itineraryDestinationId, $itineraryId, $visitTime = null, $note = null)
    {
        try {
            DB::beginTransaction();

            // Get the itinerary destination
            $itineraryDestination = ItineraryDestination::find($itineraryDestinationId);

            if (!$itineraryDestination) {
                DB::rollBack();
                return false;
            }

            // Check if user owns this itinerary
            if ($itineraryDestination->itinerary->user_id !== Auth::id()) {
                DB::rollBack();
                return false;
            }

            // Check if destination belongs to the given itinerary
            if ($itineraryDestination->itinerary_id != $itineraryId) {
                DB::rollBack();
                return false;
            }

            // Update visit_date_time with the new time (keeping the date part)
            if ($visitTime) {
                $currentVisitDateTime = Carbon::parse($itineraryDestination->visit_date_time);
                $date = $currentVisitDateTime->format('Y-m-d');
                $itineraryDestination->visit_date_time = $date . 'T' . $visitTime;
            }

            // Update note
            $itineraryDestination->note = $note;

            // Save changes
            $itineraryDestination->save();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating destination: ' . $e->getMessage());
            return false;
        }
    }
}
