<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Services\DestinationService;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    protected $destinationService;

    /**
     * konstruktor controller destinasi.
     *
     * @param DestinationService $destinationService
     */
    public function __construct(DestinationService $destinationService)
    {
        $this->destinationService = $destinationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Set default filter untuk mengurutkan berdasarkan likes (terbanyak)
        $filters = [
            'sort_by' => 'likes_desc',
            'per_page' => 12 // Biasanya halaman user menampilkan lebih banyak item
        ];

        // Tetap memungkinkan user untuk mengubah sorting jika diperlukan
        if ($request->has('sort')) {
            $filters['sort_by'] = $request->sort;
        }

        // Apply kategori filter jika ada
        if ($request->has('category')) {
            $filters['category_id'] = $request->category;
        }

        // Apply search filter jika ada
        if ($request->has('search')) {
            $filters['search'] = $request->search;
        }

        $destinations = $this->destinationService->getDestinationsList($filters);

        return view('user.destination', compact('destinations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        //

        $destination = $this->destinationService->getDestinationBySlug($slug);

        if (!$destination) {
            abort(404);
        }

        return view('user.destination-detail', compact('destination'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
