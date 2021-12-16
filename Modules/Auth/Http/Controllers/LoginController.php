<?php


namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Auth\Http\Requests\LoginRequest;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class LoginController extends Controller
{
    use AuthenticatesUsers, UsesTenantModel;

    /**
     * Show login form
     *
     * @return Response
     */
    public function showLoginForm(): Response
    {
        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }
        return Inertia::render("Auth::Login");
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
        $request->authenticate(Tenant::current()->guard);

        $request->session()->regenerate();

        return Redirect::route('projects.index');
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Redirect::route('login');
    }
}
