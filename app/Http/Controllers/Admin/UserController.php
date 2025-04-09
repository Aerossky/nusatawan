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
}
