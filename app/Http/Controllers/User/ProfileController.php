<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
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
