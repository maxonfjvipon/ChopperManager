<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // TODO: make login method that does not allow to login user with deleted = true

    /**
     * Show login form
     *
     * @return Response
     */
    public function showLoginForm(): Response
    {
        return Inertia::render("Auth/Login");
    }
}
