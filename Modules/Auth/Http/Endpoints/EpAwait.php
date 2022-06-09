<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

final class EpAwait extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return (new TkInertia("Auth::Await"))->act();
    }
}
