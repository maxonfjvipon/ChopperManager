<?php

namespace App\Takes\Auth;

use App\Interfaces\Take;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\RqRegister;
use Modules\User\Entities\User;
use Symfony\Component\HttpFoundation\Response;

final class TkRegisterUser implements Take
{
    /**
     * Ctor.
     */
    public function __construct(private Take $origin)
    {
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
