<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Destination\DestinationService;
use App\Services\DestinationSubmissionService;
use App\Services\LikeService;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //

    protected $profileService;
    protected $destinationService;
    protected $destinationSubmissionService;
    protected $likeService;

    /**
     * Konstruktor controller submission destinasi.
     *
     * @param ProfileService $ProfileService
     * @param DestinationService $destinationService
     * @param DestinationSubmissionService $destinationSubmissionService
     * @param LikeService $likeService
     */
    public function __construct(ProfileService $profileService, DestinationService $destinationService, DestinationSubmissionService $destinationSubmissionService, LikeService $likeService)
    {
        $this->profileService = $profileService;
        $this->destinationService = $destinationService;
        $this->destinationSubmissionService = $destinationSubmissionService;
        $this->likeService = $likeService;
    }

    public function show()
    {
        $profile = $this->profileService->getProfile();
        $destinationSubmissions = $this->destinationSubmissionService->getUserSubmissions($profile->id);
        $destinationUserTotal = $this->destinationService->getTotalDestinationsByUser($profile->id);
        $likedDestinationUserTotal = $this->likeService->getTotalLikesByUser($profile->id);


        return view('user.profile', compact('destinationSubmissions', 'profile', 'destinationUserTotal', 'likedDestinationUserTotal'));
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
