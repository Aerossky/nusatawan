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
    /**
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * @var DestinationSubmissionService
     */
    protected $destinationSubmissionService;

    /**
     * Constructor for destination submission controller.
     *
     * @param DestinationSubmissionService $destinationSubmissionService
     * @param CategoryService $categoryService
     */
    public function __construct(
        DestinationSubmissionService $destinationSubmissionService,
        CategoryService $categoryService
    ) {
        $this->destinationSubmissionService = $destinationSubmissionService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display all destination submissions.
     *
     * This function retrieves all destination submissions from the database
     * with optional filtering by status, category, and search term.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $filters = request()->only(['status', 'category_id', 'search']);
        $submissions = $this->destinationSubmissionService->getAllSubmissions($filters);
        $categories = $this->categoryService->getAllCategories();

        return view('admin.destination-submissions.index', compact('submissions', 'categories'));
    }

    /**
     * Show the form for editing the destination submission.
     *
     * This function loads the destination submission details and all available
     * categories to populate the edit form.
     *
     * @param DestinationSubmission $destinationSubmission
     * @return \Illuminate\View\View
     */
    public function edit(DestinationSubmission $destinationSubmission)
    {
        $categories = $this->categoryService->getAllCategories();
        $submission = $this->destinationSubmissionService->getUserSubmissionDetail($destinationSubmission);

        return view('admin.destination-submissions.edit', compact('submission', 'categories'));
    }

    /**
     * Delete the specified destination submission.
     *
     * This function removes the destination submission from the database
     * and redirects to the index page with a success message upon completion.
     * If an error occurs, the user is redirected back with an error message.
     *
     * @param DestinationSubmission $destinationSubmission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestinationSubmission $destinationSubmission)
    {
        try {
            $this->destinationSubmissionService->deleteSubmission($destinationSubmission);

            return redirect()->route('admin.destination-submissions.index')
                ->with('success', 'Destination submission successfully deleted');
        } catch (\Exception $e) {
            Log::error('Error deleting destination submission: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal menghapus pengajuan destinasi');
        }
    }

    /**
     * Approve the specified destination submission.
     *
     * This function validates the approval data, processes the approval through
     * the destination submission service, and redirects to the index page.
     * Selected images, primary image, description, and admin notes can be
     * specified during approval.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
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

            return redirect()->route('admin.destination-submission.index')
                ->with('success', 'Pengajuan destinasi berhasil disetujui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Reject the specified destination submission.
     *
     * This function validates any rejection notes, processes the rejection through
     * the destination submission service, and redirects to the index page.
     * Admin notes can be provided to explain the reason for rejection.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $validatedData = $request->validate([
            'admin_note' => 'nullable|string',
        ]);

        try {
            $this->destinationSubmissionService->rejectSubmission($id, $validatedData);

            return redirect()->route('admin.destination-submission.index')
                ->with('success', 'Pengajuan destinasi berhasil ditolak');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
