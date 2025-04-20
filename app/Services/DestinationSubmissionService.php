<?php

namespace App\Services;

use App\Models\Destination;
use App\Models\DestinationImage;
use App\Models\DestinationSubmission;
use App\Models\DestinationSubmissionImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationSubmissionService
{
    public function getAllSubmissions($filters = [])
    {
        $query = DestinationSubmission::query();

        // Filter status
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        // Filter kategori
        if (isset($filters['category_id']) && $filters['category_id'] !== '') {
            $query->where('category_id', $filters['category_id']);
        }

        // Filter pencarian nama tempat
        if (isset($filters['search']) && $filters['search'] !== '') {
            $query->where('place_name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->with('images')->latest()->paginate(10);
    }

    public function getUserSubmissions($userId)
    {
        return DestinationSubmission::where('user_id', $userId)
            ->with('images')
            ->latest()
            ->paginate(10);
    }

    public function getUserSubmissionDetail(DestinationSubmission $destinationSubmission): DestinationSubmission
    {
        return $destinationSubmission->load('images');
    }

    public function createSubmission($data, $images = [])
    {
        DB::beginTransaction();
        try {
            // Set status default sebagai pending
            $data['status'] = 'pending';

            $submission = DestinationSubmission::create([
                'place_name' => $data['place_name'],
                'description' => $data['description'],
                'category_id' => $data['category_id'],
                'created_by' => 1, // ganti dengan ID user yang sesuai
                'administrative_area' => $data['administrative_area'],
                'province' => $data['province'],
                'time_minutes' => $data['time_minutes'],
                'best_visit_time' => $data['best_visit_time'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]);

            // Simpan gambar jika ada
            if (!empty($images)) {
                foreach ($images as $image) {
                    $imagePath = $image->store('destination-submissions', 'public');
                    $submission->images()->create([
                        'url' => $imagePath
                    ]);
                }
            }

            DB::commit();
            return $submission;
        } catch (\Exception $e) {            // Log error (Debug)

            // Log::error('Service error: ' . $e->getMessage());
            DB::rollBack();
            throw $e;
        }
    }

    public function updateSubmission(DestinationSubmission $submission, $data, $images = [])
    {
        DB::beginTransaction();
        try {
            $submission->update($data);

            // Simpan gambar tambahan jika ada
            if (!empty($images)) {
                foreach ($images as $image) {
                    $imagePath = $image->store('destination-submissions', 'public');
                    $submission->images()->create([
                        'url' => $imagePath
                    ]);
                }
            }

            DB::commit();
            return $submission;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteSubmission(DestinationSubmission $submission)
    {
        DB::beginTransaction();
        try {
            // Hapus gambar-gambar terkait
            foreach ($submission->images as $image) {
                // Hapus file fisik gambar jika perlu
                if (Storage::disk('public')->exists($image->url)) {
                    Storage::disk('public')->delete($image->url);
                }
                $image->delete();
            }

            $submission->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateStatus(DestinationSubmission $submission, $status, $adminNote = null)
    {
        $submission->status = $status;

        if ($adminNote) {
            $submission->admin_note = $adminNote;
        }

        $submission->save();
        return $submission;
    }

    public function deleteImage(DestinationSubmissionImage $image)
    {
        // Hapus file fisik gambar jika perlu
        if (Storage::disk('public')->exists($image->url)) {
            Storage::disk('public')->delete($image->url);
        }

        $image->delete();
        return true;
    }

    public function approveSubmission(int $id, array $data)
    {
        $submission = DestinationSubmission::with('images')->findOrFail($id);

        // Only allow approval if submission is pending
        if ($submission->status !== 'pending') {
            throw new \Exception('Pengajuan yang sudah diproses tidak dapat disetujui');
        }

        try {
            DB::beginTransaction();

            // Create new destination
            $destination = Destination::create([
                'created_by' => $submission->created_by,
                'category_id' => $data['category_id'] ?? $submission->category_id,
                'place_name' => $submission->place_name,
                'description' => $data['description'] ?? $submission->description,
                'administrative_area' => $submission->administrative_area,
                'province' => $submission->province,
                'latitude' => $submission->latitude,
                'longitude' => $submission->longitude,
                'time_minutes' => $submission->time_minutes,
                'best_visit_time' => $submission->best_visit_time,
                'rating' => 0,
                'rating_count' => 0,
            ]);

            // Transfer selected images
            $this->transferSelectedSubmissionImages($submission, $destination, $data['selected_images'] ?? [], $data['primary_image_id']);

            // Update submission status
            $submission->update([
                'status' => 'approved',
                'admin_note' => $data['admin_note'] ?? null
            ]);

            DB::commit();

            return $destination;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rejectSubmission(int $id, array $data)
    {
        $submission = DestinationSubmission::findOrFail($id);

        // Only allow rejection if submission is pending
        if ($submission->status !== 'pending') {
            throw new \Exception('Pengajuan yang sudah diproses tidak dapat ditolak');
        }

        $submission->update([
            'status' => 'rejected',
            'admin_note' => $data['admin_note'] ?? null
        ]);

        return $submission;
    }

    /**
     * Transfer images from submission to destination
     *
     * @param DestinationSubmission $submission
     * @param Destination $destination
     * @param int $primaryIndex
     * @return void
     */
    /**
     * Transfer selected images from submission to destination
     *
     * @param DestinationSubmission $submission
     * @param Destination $destination
     * @param array $selectedImageIds
     * @param int $primaryImageId
     * @return void
     */
    private function transferSelectedSubmissionImages(DestinationSubmission $submission, Destination $destination, array $selectedImageIds, int $primaryImageId): void
    {
        // If no images selected, use all images
        if (empty($selectedImageIds)) {
            $selectedImageIds = $submission->images->pluck('id')->toArray();
        }

        // Process only selected images
        foreach ($submission->images as $image) {
            // Skip if not selected
            if (!in_array($image->id, $selectedImageIds)) {
                continue;
            }

            $isPrimary = ($image->id == $primaryImageId);

            // Check if file exists in storage
            if (Storage::exists($image->url)) {
                // Get file extension from existing path
                $extension = pathinfo($image->url, PATHINFO_EXTENSION);

                // Generate new filename
                $filename = uniqid() . '-' . Str::random(10) . '-' . time() . '.' . $extension;
                $newPath = 'destinations/' . $filename;

                // Copy file to new location in storage
                if (Storage::copy($image->url, $newPath)) {
                    // Create new destination image record
                    DestinationImage::create([
                        'destination_id' => $destination->id,
                        'url' => $newPath,
                        'name' => $image->name ?? basename($image->url),
                        'is_primary' => $isPrimary,
                    ]);
                }
            }
        }
    }
}
