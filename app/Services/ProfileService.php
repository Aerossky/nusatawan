<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileService
{
    /**
     * Mengambil data profil pengguna yang sedang login.
     *
     * @return \App\Models\User
     */
    public function getProfile()
    {
        return Auth::user();
    }

    /**
     * Memperbarui data profil pengguna.
     *
     * @param \App\Models\User $user
     * @param array $data
     * @return \App\Models\User
     */
    public function updateProfile(User $user, array $data)
    {
        // Hapus debugging code di production
        // @dd($data);

        $this->handleProfileImage($user, $data);
        $this->updateUserDetails($user, $data);

        $user->save();

        return $user;
    }

    /**
     * menangani gambar profil pengguna
     *
     * @param User $user
     * @param array $data
     * @return void
     */
    private function handleProfileImage(User $user, array $data): void
    {
        if (!isset($data['image']) || !$data['image']) {
            return;
        }

        $this->removeExistingImage($user);
        $user->image = $this->uploadImage($data['image']);
    }

    /**
     * menghapus gambar profil pengguna yang sudah ada
     *
     * @param User $user
     * @return void
     */
    private function removeExistingImage(User $user): void
    {
        if (!$user->image) {
            return;
        }

        $oldImagePath = public_path('storage/' . $user->image);
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }

    /**
     * memperbarui detail pengguna
     *
     * @param User $user
     * @param array $data
     * @return void
     */
    private function updateUserDetails(User $user, array $data): void
    {
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
    }

    /**
     * mengunggah gambar profil pengguna
     *
     * @param UploadedFile $image
     * @return string
     */
    private function uploadImage($image): string
    {
        $imageName = uniqid() . '-' . Str::random(10) . '-' . time() . '.' . $image->extension();
        $path = $image->storeAs('users', $imageName);

        return str_replace('', '', $path);
    }
}
