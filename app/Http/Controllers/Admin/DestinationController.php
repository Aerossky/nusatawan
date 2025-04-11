<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\DestinationService;
use Illuminate\Http\Request;

class DestinationController extends Controller
{

    protected $destinationService;

    public function __construct(DestinationService $destinationService)
    {
        $this->destinationService = $destinationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->query('search'),
            'category_id' => $request->query('category_id'),
            'sort_by' => $request->query('sort_by'),
            'per_page' => $request->query('per_page', 10)
        ];

        $destinations = $this->destinationService->getDestinationsList($filters);
        $categories = Category::all();

        return view('admin.destinations.index', compact('destinations', 'categories', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // kategori
        $categories = Category::all();

        return view('admin.destinations.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // validasi
        $validated = $request->validate([
            'place_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'time_minutes' => 'required|integer|min:0',
            'city' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'required|string',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image_index' => 'required|integer|min:0',
        ]);

        // simpan data
        $destination = $this->destinationService->createDestination($validated);

        if ($destination) {
            // jika berhasil
            return redirect()->route('admin.destinations.index')->with('success', 'Destinasi berhasil dibuat.');
        } else {
            // jika gagal
            return redirect()->back()->with('error', 'Gagal membuat destinasi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
