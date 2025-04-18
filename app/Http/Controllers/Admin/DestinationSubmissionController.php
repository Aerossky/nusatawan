<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\DestinationSubmissionService;
use Illuminate\Http\Request;

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
        // Mengambil data pengajuan destinasi dan kategori dari service
        $submissions = $this->destinationSubmissionService->getAllSubmissions();
        $categories = $this->categoryService->getAllCategories();

        return view('admin.destination-submissions.index', compact('submissions', 'categories'));
    }

    public function approve(Request $request, $id)
    {
        $validatedData = $request->validate([
            'description' => 'nullable|string',
            'admin_note' => 'nullable|string',
            'primary_image_index' => 'nullable|integer|min:0',
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
