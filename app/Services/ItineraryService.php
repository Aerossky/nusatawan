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
 * Service untuk mengelola rencana perjalanan wisata dan destinasinya.
 * Menyediakan fungsi CRUD dan operasi lain yang terkait dengan rencana perjalanan.
 *
 * @package App\Services
 */
class ItineraryService
{
    /**
     * Mendapatkan semua rencana perjalanan milik pengguna yang sedang login dengan berbagai filter dan opsi pengurutan
     *
     * @param array $filters Array filter dengan kemungkinan kunci:
     *                      - 'search': Pencarian berdasarkan judul
     *                      - 'status': Filter berdasarkan status
     *                      - 'sort': Metode pengurutan ('oldest', 'title_asc', 'title_desc', atau default terbaru ke terlama)
     * @return \Illuminate\Pagination\LengthAwarePaginator Objek rencana perjalanan dengan paginasi dan jumlah destinasi
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
     * Mendapatkan rencana perjalanan tertentu beserta destinasinya
     *
     * @param int $id ID rencana perjalanan
     * @return \App\Models\Itinerary Rencana perjalanan dengan relasi destinasi yang dimuat
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika rencana perjalanan tidak ditemukan atau bukan milik pengguna
     */
    public function getItinerary($id)
    {
        return Itinerary::with('itineraryDestinations.destination')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
    }

    /**
     * Membuat rencana perjalanan baru untuk pengguna yang sedang login
     *
     * @param array $data Data rencana perjalanan
     * @return \App\Models\Itinerary Rencana perjalanan yang baru dibuat
     */
    public function createItinerary(array $data)
    {
        $data['user_id'] = Auth::id();

        // membuat itinerary baru
        return  Itinerary::create($data);
    }

    /**
     * Memperbarui waktu kunjungan dan catatan untuk destinasi dalam rencana perjalanan
     *
     * @param int $itineraryDestinationId ID destinasi rencana perjalanan
     * @param int $itineraryId ID rencana perjalanan
     * @param string|null $visitTime Waktu kunjungan baru (opsional)
     * @param string|null $note Catatan baru (opsional)
     * @return bool True jika pembaruan berhasil, false jika tidak
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

    /**
     * Menghapus rencana perjalanan dan destinasi terkaitnya
     *
     * @param int $id ID rencana perjalanan yang akan dihapus
     * @return bool True jika penghapusan berhasil, false jika tidak
     */
    public function deleteItinerary($id)
    {
        DB::beginTransaction();

        try {
            // Find the itinerary
            $itinerary = Itinerary::where('user_id', Auth::id())->findOrFail($id);

            // Delete associated destinations
            $itinerary->itineraryDestinations()->delete();

            // Delete the itinerary
            $itinerary->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting itinerary: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Menambahkan destinasi ke rencana perjalanan yang ada
     *
     * @param array $data Data yang berisi:
     *                   - 'itinerary_id': ID rencana perjalanan
     *                   - 'destination_id': ID destinasi (opsional)
     *                   - 'visit_date_time': Tanggal/waktu kunjungan yang direncanakan (opsional)
     *                   - 'order_index': Urutan dalam rencana perjalanan (opsional, akan dihitung jika tidak disediakan)
     *                   - 'note': Catatan tambahan (opsional)
     * @return array Detail destinasi yang ditambahkan
     * @throws \Exception Jika operasi gagal
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
     * Menghapus destinasi dari rencana perjalanan
     *
     * @param array $data Data yang berisi:
     *                   - 'itinerary_id': ID rencana perjalanan
     *                   - 'destination_id': ID destinasi rencana perjalanan yang akan dihapus
     * @return array Status operasi
     * @throws \Exception Jika operasi gagal
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
     * Memperbarui rencana perjalanan yang ada
     *
     * @param array $data Data yang berisi:
     *                   - 'id': ID rencana perjalanan yang akan diperbarui
     *                   - Kolom lain yang akan diperbarui
     * @return \App\Models\Itinerary Rencana perjalanan yang diperbarui
     * @throws \Exception Jika operasi gagal
     */
    public function updateItinerary(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            // Mencari itinerary berdasarkan ID
            $itinerary = Itinerary::findOrFail($id);

            // Memperbarui itinerary
            $itinerary->update($data);

            DB::commit();

            return $itinerary;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update itinerary: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mendapatkan destinasi tertentu berdasarkan ID-nya
     *
     * @param int $itineraryDestinationId ID destinasi rencana perjalanan
     * @return array|null Detail destinasi atau null jika tidak ditemukan atau tidak dapat diakses
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
}
