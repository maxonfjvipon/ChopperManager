<?php

namespace Modules\Auth\Takes;

use App\Interfaces\Take;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\RqRegister;
use Modules\User\Entities\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * Take where user is being registered.
 *
 * @see AuthEndpointsTest
 */
final class TkRegisterUser implements Take
{
    private Take $origin;

    /**
     * Ctor.
     */
    public function __construct(Take $take)
    {
        $this->origin = $take;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function act(RqRegister|Request $request = null): Responsable|Response
    {
        $user = User::create($request->userProps());

        event(new Registered($user));
        Auth::guard()->login($user);

        return $this->origin->act($request);
    }
}
