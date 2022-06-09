<?php

namespace App\Takes\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Takes\Take;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class TkRegisterUser implements Take
{
    /**
     * Ctor.
     * @param Take $origin
     */
    public function __construct(private Take $origin)
    {
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function act(RegisterRequest|Request $request = null): Responsable|Response
    {
        $user = User::create($request->userProps());

        event(new Registered($user));
        Auth::guard()->login($user);

        return $this->origin->act($request);
    }
}
