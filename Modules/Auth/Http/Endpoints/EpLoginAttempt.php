<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkEnvelope;
use App\Takes\TkFromCallback;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Auth\Http\Requests\RqLogin;
use Modules\Project\Takes\TkRedirectToProjectsIndex;
use Symfony\Component\HttpFoundation\Response;

/**
 * Login attempt endpoint
 * @package Modules\Auth\Takes
 */
final class EpLoginAttempt extends TakeEndpoint
{
    use AuthenticatesUsers;

//    public function __invoke(RqLogin $request): Responsable|Response
//    {
//        return (new TkWithCallback(
//            function () use ($request) {
//                $request->authenticate();
//                $request->session()->regenerate();
//            },
//            new TkRedirectToProjectsIndex()
//        ))->act($request);
//    }

    /**
     * Ctor.
     * @param RqLogin $request
     */
    public function __construct(private RqLogin $request)
    {
        parent::__construct(
            new TkFromCallback(
                fn() => new TkWithCallback(
                    function () {
                        $this->request->authenticate();
                        $this->request->session()->regenerate();
                    },
                    new TkRedirectToProjectsIndex()
                )
            )
        );
    }
}
