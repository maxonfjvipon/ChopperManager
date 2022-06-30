<?php

namespace Modules\User\Http\Endpoints\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\RqUpdateProfile;
use Redirect;

/**
 * Update profile endpoint.
 */
final class EpUpdateProfile extends Controller
{
    /**
     * @param RqUpdateProfile $request
     * @return RedirectResponse
     */
    public function __invoke(RqUpdateProfile $request): RedirectResponse
    {
        Auth::user()->update($request->validated());
        return Redirect::back()->with('success', __('flash.users.updated'));
    }
}
