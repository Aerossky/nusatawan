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

    public function index(Request $request)
    {
        $userId = Auth::id();
        $perPage = $request->get('per_page', 9);
        $favorites = $this->likeService->getLikedDestinations($userId, $perPage);


        return view('user.destination-favorite', compact('favorites'));
    }
}
