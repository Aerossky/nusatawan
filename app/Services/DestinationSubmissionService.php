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
    /**
     * Get all submissions with optional filters
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllSubmissions(array $filters = [])
    {
        $query = DestinationSubmission::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['search'])) {
            $query->where('place_name', 'like', "%{$filters['search']}%");
        }

        return $query->with('images')->latest()->paginate(10);
    }

    /**
     * Get submissions for a specific user
     *
     * @param int $userId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserSubmissions(int $userId)
    {
        return DestinationSubmission::where('user_id', $userId)
            ->with('images')
            ->latest()
            ->paginate(10);
    }

    /**
     * Get detailed information about a user's submission
     *
     * @param DestinationSubmission $destinationSubmission
     * @return DestinationSubmission
     */
    public function getUserSubmissionDetail(DestinationSubmission $destinationSubmission): DestinationSubmission
    {
        return $destinationSubmission->load('images');
    }

    /**
     * Create a new destination submission
     *
     * @param array $data
     * @param array $images
     * @return DestinationSubmission
     * @throws \Exception
     */
    public function createSubmission(array $data, array $images = [])
    {
        DB::beginTransaction();

        try {
            $data['status'] = 'pending';

            $submission = DestinationSubmission::create([
                'place_name' => $data['place_name'],
                'description' => $data['description'],
                'category_id' => $data['category_id'],
                'created_by' => 1, // TODO: Replace with appropriate user ID
                'administrative_area' => $data['administrative_area'],
                'province' => $data['province'],
                'time_minutes' => $data['time_minutes'],
                'best_visit_time' => $data['best_visit_time'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]);

            $this->saveSubmissionImages($submission, $images);

            DB::commit();
            return $submission;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create destination submission: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing submission
     *
     * @param DestinationSubmission $submission
     * @param array $data
     * @param array $images
     * @return DestinationSubmission
     * @throws \Exception
     */
    public function updateSubmission(DestinationSubmission $submission, array $data, array $images = [])
    {
        DB::beginTransaction();

        try {
            $submission->update($data);
            $this->saveSubmissionImages($submission, $images);

            DB::commit();
            return $submission;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update destination submission: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a submission and its associated images
     *
     * @param DestinationSubmission $submission
     * @return bool
     * @throws \Exception
     */
    public function deleteSubmission(DestinationSubmission $submission)
    {
        DB::beginTransaction();

        try {
            foreach ($submission->images as $image) {
                $this->deleteImage($image);
            }

            $submission->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete destination submission: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update the status of a submission
     *
     * @param DestinationSubmission $submission
     * @param string $status
     * @param string|null $adminNote
     * @return DestinationSubmission
     */
    public function updateStatus(DestinationSubmission $submission, string $status, ?string $adminNote = null)
    {
        $submission->status = $status;

        if ($adminNote) {
            $submission->admin_note = $adminNote;
        }

        $submission->save();
        return $submission;
    }

    /**
     * Delete a submission image
     *
     * @param DestinationSubmissionImage $image
     * @return bool
     */
    public function deleteImage(DestinationSubmissionImage $image)
    {
        if (Storage::disk('public')->exists($image->url)) {
            Storage::disk('public')->delete($image->url);
        }

        $image->delete();
        return true;
    }

    /**
     * Approve a destination submission
     *
     * @param int $id
     * @param array $data
     * @return Destination
     * @throws \Exception
     */
    public function approveSubmission(int $id, array $data)
    {
        $submission = DestinationSubmission::with('images')->findOrFail($id);

        if ($submission->status !== 'pending') {
            throw new \Exception('Pengajuan yang sudah diproses tidak dapat disetujui');
        }

        try {
            DB::beginTransaction();

            $destination = $this->createDestinationFromSubmission($submission, $data);
            $this->transferSelectedSubmissionImages($submission, $destination, $data['selected_images'] ?? [], $data['primary_image_id']);

            $submission->update([
                'status' => 'approved',
                'admin_note' => $data['admin_note'] ?? null
            ]);

            DB::commit();
            return $destination;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve destination submission: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reject a destination submission
     *
     * @param int $id
     * @param array $data
     * @return DestinationSubmission
     * @throws \Exception
     */
    public function rejectSubmission(int $id, array $data)
    {
        $submission = DestinationSubmission::findOrFail($id);

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
     * Save images for a submission
     *
     * @param DestinationSubmission $submission
     * @param array $images
     * @return void
     */
    private function saveSubmissionImages(DestinationSubmission $submission, array $images): void
    {
        if (empty($images)) {
            return;
        }

        foreach ($images as $image) {
            $imagePath = $image->store('destination-submissions', 'public');
            $submission->images()->create([
                'url' => $imagePath
            ]);
        }
    }

    /**
     * Create a destination from a submission
     *
     * @param DestinationSubmission $submission
     * @param array $data
     * @return Destination
     */
    private function createDestinationFromSubmission(DestinationSubmission $submission, array $data): Destination
    {
        return Destination::create([
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
    }

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
        if (empty($selectedImageIds)) {
            $selectedImageIds = $submission->images->pluck('id')->toArray();
        }

        foreach ($submission->images as $image) {
            if (!in_array($image->id, $selectedImageIds)) {
                continue;
            }

            $isPrimary = ($image->id == $primaryImageId);

            if (!Storage::exists($image->url)) {
                continue;
            }

            $extension = pathinfo($image->url, PATHINFO_EXTENSION);
            $filename = uniqid() . '-' . Str::random(10) . '-' . time() . '.' . $extension;
            $newPath = 'destinations/' . $filename;

            if (Storage::copy($image->url, $newPath)) {
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
