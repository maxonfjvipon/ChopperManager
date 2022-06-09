<?php

namespace Modules\Auth\Takes;

use App\Takes\Take;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\RegisterUserRequest;
use Modules\User\Entities\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * Take where user is being registered.
 * @package Modules\Auth\Takes\Deep
 * @see AuthEndpointsTest
 */
final class TkRegisteredUser implements Take
{
    /**
     * @var Take $origin
     */
    private Take $origin;

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
     * @throws Exception
     */
    public function act(RegisterUserRequest|Request $request = null): Responsable|Response
    {
        $user = User::create($request->userProps());

        event(new Registered($user));
        Auth::guard()->login($user);

        return $this->origin->act($request);
    }
}
