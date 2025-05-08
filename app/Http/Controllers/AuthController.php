<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show registration form
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload data pengguna
        $user = $this->authService->register($validatedData);

        // Login otomatis setelah register
        Auth::login($user);

        return redirect()->route('user.home')->with('success', 'Pendaftaran berhasil, selamat datang!');
    }

    /**
     * Show login form
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Authenticate user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            // Remember me functionality
            $remember = $request->has('remember');

            // Attempt login with credentials
            $user = $this->authService->login($credentials, $remember);

            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Redirect based on user role
            if ($user->isAdmin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('user.home'))->with('success', 'Selamat datang, ' . $user->name . '!');
        } catch (ValidationException $e) {
            return back()->withErrors([
                'email' => $e->getMessage(),
            ])->withInput($request->except('password'));
        }
    }

    /**
     * Logout user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->authService->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Redirect ke halaman login
        return redirect()->route('auth.login')->with('success', 'Anda telah berhasil keluar.');
    }
}
