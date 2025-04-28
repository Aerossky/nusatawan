<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DestinationSubmission;
use App\Services\CategoryService;
use App\Services\DestinationSubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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


    /**
     * Menampilkan form untuk membuat pengajuan destinasi.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('user.destination-submission', compact('categories'));
    }

    /**
     * Membuat pengajuan destinasi berdasarkan data yang diterima dari form.
     *
     * Fungsi ini akan memvalidasi data yang diterima dan jika valid,
     * akan membuat pengajuan destinasi melalui service, lalu redirect ke halaman
     * detail pengajuan destinasi yang baru dibuat.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validasi data yang diterima dari form
            $data = $this->validateDestination($request);

            // user_id diambil dari auth user
            $data['user_id'] = Auth::user()->id;
            $images = $request->file('images') ?? [];

            $submission = $this->destinationSubmissionService->createSubmission($data, $images);

            return redirect()->route('destination-submissions.show', $submission)
                ->with('success', 'Pengajuan destinasi berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Form submission error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Melakukan validasi terhadap data yang diterima dari form submission destinasi.
     *
     * Validasi ini akan memastikan bahwa data yang diterima sesuai dengan
     * kriteria yang diharapkan. Jika data valid, maka akan dikembalikan
     * sebagai array yang berisi data yang telah divalidasi.
     *
     * @param Request $request
     * @return array
     */
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
