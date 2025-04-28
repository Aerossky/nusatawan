<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\LikeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    //
    /**
     * @var LikeService
     */
    protected $likeService;

    /**
     * Constructor
     */
    public function __construct(LikeService $LikeService)
    {
        $this->likeService = $LikeService;
    }

    /**
     * Menampilkan halaman daftar destinasi favorit dari user yang sedang login.
     * Data diambil dari LikeService dan diurutkan berdasarkan parameter 'sort'
     * yang diterima dari request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $perPage = $request->get('per_page', 9);
        $sort = $request->get('sort', 'latest'); // Default 'latest'

        $favorites = $this->likeService->getLikedDestinations($userId, $perPage, $sort);

        return view('user.destination-favorite', compact('favorites'));
    }
}
