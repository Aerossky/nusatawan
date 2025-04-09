<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * Constructor
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a list of users with optional filters for search and status.
     *
     * @param Request $request The incoming request containing filter parameters.
     * @return \Illuminate\Contracts\View\View The view displaying the list of users.
     */

    public function index(Request $request)
    {
        // Persiapkan filter dari request
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'per_page' => 10
        ];

        // Ambil daftar pengguna menggunakan service
        $users = $this->userService->getUsersList($filters);

        // Kembalikan view dengan data pengguna
        return view('admin.users.index', [
            'users' => $users
        ]);
    }


    /**
     * Menampilkan form untuk membuat pengguna baru
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        // Kembalikan view untuk membuat pengguna baru
        return view('admin.users.create');
    }

    /**
     * Menyimpan pengguna baru ke dalam database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data pengguna
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable|image|max:2048',
        ]);


        // Buat pengguna baru menggunakan service
        $this->userService->createUser($validatedData);

        // Redirect ke halaman daftar pengguna dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pengguna
     *
     * @param User $user pengguna yang akan ditampilkan
     * @return \Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        // Ambil detail pengguna menggunakan service
        $userDetails = $this->userService->getUserDetails($user);

        // Kembalikan view dengan data pengguna
        return view('admin.users.show', [
            'user' => $userDetails,
        ]);
    }

    /**
     * Menampilkan form untuk mengedit pengguna
     *
     * @param User $user pengguna yang akan diedit
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {
        // Ambil detail pengguna menggunakan service
        $userDetails = $this->userService->getUserDetails($user);

        // Kembalikan view dengan data pengguna
        return view('admin.users.edit', [
            'user' => $userDetails,
        ]);
    }

    /**
     * Mengupdate pengguna yang sudah ada
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|max:2048',
            'status' => 'nullable|in:active,inactive,banned',
            'isAdmin' => 'nullable|boolean'
        ]);

        // Buat data yang mau diupdate aja (auto skip null / kosong)
        $dataToUpdate = array_filter($validatedData, function ($value) {
            return $value !== null;
        });

        // Jalankan update lewat service
        $this->userService->updateUser($user, $dataToUpdate);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            // Hapus pengguna menggunakan service
            $this->userService->deleteUser($user);

            // Redirect ke halaman daftar pengguna dengan pesan sukses
            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            // Redirect kembali dengan pesan error
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
