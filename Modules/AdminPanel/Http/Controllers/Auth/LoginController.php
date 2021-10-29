<?php

namespace Modules\AdminPanel\Http\Controllers\Auth;

use App\Traits\InModule;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Http\Requests\LoginRequest;

class LoginController extends Controller
{

    use AuthenticatesUsers, InModule;

    /**
     * Show login form
     *
     * @return Response
     */
    public function showLoginForm(): Response
    {
        return Inertia::render('AdminPanel::Login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return Redirect::route('admin.index');
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Redirect::route('admin.login');
    }
}
