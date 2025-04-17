<?php

namespace App\Services;

use App\Models\DestinationSubmission;
use App\Models\DestinationSubmissionImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DestinationSubmissionService
{
    public function getAllSubmissions($filters = [])
    {
        $query = DestinationSubmission::query();

        // Tambahkan filter jika ada
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Bisa tambahkan filter lainnya sesuai kebutuhan

        return $query->with('images')->latest()->paginate(10);
    }

    public function getUserSubmissions($userId)
    {
        return DestinationSubmission::where('user_id', $userId)
            ->with('images')
            ->latest()
            ->paginate(10);
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
}
