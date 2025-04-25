<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
     * Membuat review baru untuk destinasi yang ditentukan.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $destinationId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $destinationId)
    {
        try {
            $validated = $request->validate([
                'rating'  => 'nullable|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            $this->reviewService->submitReview($destinationId, $validated);

            return redirect()
                ->route('user.destinations.show', $destinationId)
                ->with('success', 'Review berhasil dikirim');
        } catch (\Exception $e) {
            Log::error('Form submission error: ' . $e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
