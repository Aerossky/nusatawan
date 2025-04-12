<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use App\Models\DestinationImage;
use App\Services\DestinationService;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    protected $destinationService;

    /**
     * Konstruktor controller destinasi.
     *
     * @param DestinationService $destinationService
     */
    public function __construct(DestinationService $destinationService)
    {
        $this->destinationService = $destinationService;
    }

    /**
     * Menampilkan daftar destinasi berdasarkan filter pencarian.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = [
            'search'      => $request->query('search'),
            'category_id' => $request->query('category_id'),
            'sort_by'     => $request->query('sort_by'),
            'per_page'    => $request->query('per_page', 10),
        ];

        $destinations = $this->destinationService->getDestinationsList($filters);
        $categories = Category::all();

        return view('admin.destinations.index', compact('destinations', 'categories', 'filters'));
    }

    /**
     * Menampilkan halaman form tambah destinasi.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.destinations.create', compact('categories'));
    }

    /**
     * Menyimpan data destinasi baru ke database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $this->validateDestination($request);

        $destination = $this->destinationService->createDestination($validated);

        return $destination
            ? redirect()->route('admin.destinations.index')->with('success', 'Destinasi berhasil dibuat.')
            : redirect()->back()->with('error', 'Gagal membuat destinasi.');
    }

    /**
     * Menampilkan halaman edit destinasi.
     *
     * @param Destination $destination
     * @return \Illuminate\View\View
     */
    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', [
            'destination' => $this->destinationService->getDestinationDetails($destination),
            'categories'  => Category::all(),
        ]);
    }

    /**
     * Memperbarui data destinasi yang sudah ada.
     *
     * @param Request $request
     * @param Destination $destination
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Destination $destination)
    {
        $validated = $this->validateDestination($request);

        try {
            $this->destinationService->updateDestination($destination, $validated);
            return redirect()->route('admin.destinations.index')->with('success', 'Destinasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['image' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Menghapus destinasi (belum diimplementasikan).
     *
     * @param string $id
     */
    public function destroy(Destination $destination)
    {
        $this->destinationService->deleteDestination($destination);
        return redirect()->route('admin.destinations.index')->with('success', 'Destinasi berhasil dihapus.');
    }

    /**
     * Menghapus gambar tertentu dari destinasi.
     *
     * @param Destination $destination
     * @param DestinationImage $image
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyImage(Destination $destination, DestinationImage $image)
    {
        $deleted = $this->destinationService->deleteImage($destination, $image);

        if (!$deleted) {
            return back()->with('error', 'Gagal menghapus gambar.');
        }

        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    /**
     * Validasi data destinasi dari form.
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
            'city'                => 'required|string|max:255',
            'latitude'            => 'required|numeric|between:-90,90',
            'longitude'           => 'required|numeric|between:-180,180',
            'description'         => 'required|string',
            'image.*'             => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image_index' => 'required|integer|min:0',
        ]);
    }
}
