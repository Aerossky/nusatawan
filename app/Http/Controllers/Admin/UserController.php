<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
     * Menampilkan daftar pengguna
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
}
