<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //

    protected $profileService;

    /**
     * Konstruktor controller submission destinasi.
     *
     * @param ProfileService $ProfileService
     */
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show()
    {
        $profile = $this->profileService->getProfile();

        return view('user.profile', compact('profile'));
    }
}
