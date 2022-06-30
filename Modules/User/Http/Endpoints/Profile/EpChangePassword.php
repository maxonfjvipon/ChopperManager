<?php

namespace Modules\User\Http\Endpoints\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\RqChangePassword;

/**
 * Change password endpoint.
 */
final class EpChangePassword extends Controller
{
    /**
     * @param RqChangePassword $request
     * @return RedirectResponse
     */
    public function __invoke(RqChangePassword $request)
    {
        if ($request->has('password') && !!$request->password) {
            Auth::user()->update(['password' => Hash::make($request->password)]);
            return \Redirect::back()->with('success', __('flash.users.password_changed'));
        }
        return \Redirect::back();
    }
}
