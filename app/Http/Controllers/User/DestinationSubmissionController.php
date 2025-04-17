<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DestinationSubmission;
use App\Services\CategoryService;
use App\Services\DestinationSubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DestinationSubmissionController extends Controller
{
    protected $destinationSubmissionService;
    protected $categoryService;

    /**
     * Konstruktor controller submission destinasi.
     *
     * @param destinationSubmissionService $destinationSubmissionService
     */
    public function __construct(DestinationSubmissionService $destinationSubmissionService, CategoryService $categoryService)
    {
        $this->destinationSubmissionService = $destinationSubmissionService;
        $this->categoryService = $categoryService;
    }

    public function index() {}

    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('user.destination-submission', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            // Log error (Debug)
            Log::info('Form data received', $request->all());
            $data = $this->validateDestination($request);

            $images = $request->file('images') ?? [];

            $submission = $this->destinationSubmissionService->createSubmission($data, $images);

            return redirect()->route('destination-submissions.show', $submission)
                ->with('success', 'Pengajuan destinasi berhasil dibuat.');
        } catch (\Exception $e) {
            // Log error (Debug)
            Log::error('Form submission error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    protected function validateDestination(Request $request): array
    {
        return $request->validate([
            'place_name'          => 'required|string|max:255',
            'category_id'         => 'required|exists:categories,id',
            'time_minutes'        => 'required|integer|min:0',
            'best_visit_time'     => 'required|string|max:255',
            'administrative_area' => 'required|string|max:255',
            'province'            => 'required|string|max:255',
            'latitude'            => 'required|numeric|between:-90,90',
            'longitude'           => 'required|numeric|between:-180,180',
            'description'         => 'required|string',
            'images.*'             => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }
}
