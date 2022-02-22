<?php

namespace Modules\Auth\Takes;

use App\Support\Take;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\RegisterUserRequest;
use Modules\User\Entities\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * Endpoint where user is being registered.
 * @package Modules\Auth\Takes\Deep
 */
final class TkRegisteredUser implements Take
{
    /**
     * @var Take $origin
     */
    private Take $origin;

    /**
     * Ctor wrap.
     * @param Take $take
     * @return TkRegisteredUser
     */
    public static function new(Take $take): TkRegisteredUser
    {
        return new self($take);
    }

    /**
     * Ctor.
     * @param Take $take
     */
    public function __construct(Take $take)
    {
        $this->origin = $take;
    }

    /**
     * @inheritDoc
     */
    public function act(RegisterUserRequest|Request $request = null): Responsable|Response
    {
        $user = User::create($request->userProps());

        event(new Registered($user));
        Auth::guard()->login($user);

        return $this->origin->act($request);
    }
}
