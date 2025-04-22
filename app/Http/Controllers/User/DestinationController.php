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
    public function index()
    {
        //
        // mengambil data destinasi dari service
        $destinations = $this->destinationService->getDestinationsList();

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
