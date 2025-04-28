<?php

namespace App\Http\Controllers;

use App\Services\LikeService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
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

    
}
