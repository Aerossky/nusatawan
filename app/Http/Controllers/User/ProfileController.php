<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DestinationService;
use App\Services\LikeService;
use App\Services\ProfileService;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //

    protected $profileService;
    protected $destinationService;
    protected $likeService;

    /**
     * Konstruktor controller submission destinasi.
     *
     * @param ProfileService $ProfileService
     * @param DestinationService $destinationService
     */
    public function __construct(ProfileService $profileService, DestinationService $destinationService, LikeService $likeService)
    {
        $this->profileService = $profileService;
        $this->destinationService = $destinationService;
        $this->likeService = $likeService;
    }

    public function show()
    {
        $profile = $this->profileService->getProfile();
        $destinationUserTotal = $this->destinationService->getTotalDestinationsByUser($profile->id);
        $likedDestinationUserTotal = $this->likeService->getTotalLikesByUser($profile->id);


        return view('user.profile', compact('profile', 'destinationUserTotal', 'likedDestinationUserTotal'));
    }

    public function update(User $user, Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable',
        ]);

        $user = $this->profileService->updateProfile($user, $validatedData);

        // Redirect ke halaman profil dengan pesan sukses
        return redirect()->route('user.profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
