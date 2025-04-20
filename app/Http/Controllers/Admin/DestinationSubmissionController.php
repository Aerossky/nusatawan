<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DestinationSubmission;
use App\Services\CategoryService;
use App\Services\DestinationSubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DestinationSubmissionController extends Controller
{
    protected $categoryService;
    protected $destinationSubmissionService;

    /**
     * Konstruktor controller submission destinasi.
     *
     * @param destinationSubmissionService $destinationSubmissionService
     * @param categoryService $categoryService
     */
    public function __construct(DestinationSubmissionService $destinationSubmissionService, CategoryService $categoryService)
    {
        $this->destinationSubmissionService = $destinationSubmissionService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $filters = request()->only(['status', 'category_id', 'search']);

        $submissions = $this->destinationSubmissionService->getAllSubmissions($filters);
        $categories = $this->categoryService->getAllCategories();

        return view('admin.destination-submissions.index', compact('submissions', 'categories'));
    }

    public function edit(DestinationSubmission $destinationSubmission)
    {
        // Mengambil data pengajuan destinasi dan kategori dari service
        $categories = $this->categoryService->getAllCategories();
        // Mengambil data pengajuan destinasi berdasarkan ID
        $submission = $this->destinationSubmissionService->getUserSubmissionDetail($destinationSubmission);

        return view('admin.destination-submissions.edit', compact('submission', 'categories'));
    }

    public function destroy(DestinationSubmission $destinationSubmission)
    {
        try {
            $this->destinationSubmissionService->deleteSubmission($destinationSubmission);
            return redirect()->route('admin.destination-submissions.index')
                ->with('success', 'Pengajuan destinasi berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting destination submission: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus pengajuan destinasi');
        }
    }

    public function approve(Request $request, $id)
    {
        $validatedData = $request->validate([
            'description' => 'nullable|string',
            'admin_note' => 'nullable|string',
            'selected_images' => 'nullable|array',
            'selected_images.*' => 'integer|exists:destination_submission_images,id',
            'primary_image_id' => 'required|integer|exists:destination_submission_images,id',
        ]);

        try {
            $this->destinationSubmissionService->approveSubmission($id, $validatedData);

            return redirect()->route('admin.destination-submissions.index')
                ->with('success', 'Pengajuan destinasi berhasil disetujui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $validatedData = $request->validate([
            'admin_note' => 'nullable|string',
        ]);

        try {
            $this->destinationSubmissionService->rejectSubmission($id, $validatedData);
            return redirect()->route('admin.destination-submissions.index')
                ->with('success', 'Pengajuan destinasi berhasil ditolak');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
