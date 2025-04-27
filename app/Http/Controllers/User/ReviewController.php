<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    //

    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
        // $this->middleware('auth');
    }


    /**
     * Mengirimkan review untuk destinasi yang ditentukan.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Destination $destination
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Destination $destination)
    {

        try {
            $validated = $request->validate([
                'rating'  => 'nullable|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            $this->reviewService->submitReview($destination->id, $validated);

            return redirect()
                ->route('user.destinations.show', $destination->slug)
                ->with('success', 'Review berhasil dikirim');
        } catch (\Exception $e) {
            Log::error('Form submission error: ' . $e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus review yang ditentukan dan update rata-rata rating destinasi terkait.
     *
     * @param Review $review Review yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman destinasi dengan pesan sukses
     */

    public function destroy(Destination $destination, Review $review)
    {
        $this->reviewService->destroyReview($destination, $review);

        return redirect()
            ->route('admin.destinations.edit', $destination)
            ->with('success', 'Review berhasil dihapus');
    }
}
