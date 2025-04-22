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
     * Mengupdate data profil pengguna yang sedang login.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function updateProfile(User $user, array $data)
    {
        // Ambil user yang sedang login (nggak perlu lagi findOrFail kalau $user sudah dikirim dari controller dengan route-model binding)

        // Handle upload image jika ada
        if (isset($data['image']) && $data['image']) {

            // Hapus gambar lama jika ada
            if ($user->image) {
                $oldImagePath =  public_path('storage/' . $user->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // simpan gambar baru
            $imagePath = $this->uploadImage($data['image']);
            $user->image = $imagePath;
        }

        // Update password jika ada
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        // Update name dan email
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['email'])) {
            $user->email = $data['email'];
        }

        // Simpan semua perubahan
        $user->save();

        return $user;
    }



    private function uploadImage($image)
    {
        // Generate nama gambar unik & random
        $imageName = uniqid() . '-' . Str::random(10) . '-' . time() . '.' . $image->extension();
        $path = $image->storeAs('users', $imageName);

        // Return path yang dapat diakses oleh public
        return str_replace('', '', $path);
    }
}
