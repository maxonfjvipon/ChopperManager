<?php

namespace Modules\Auth\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\User\Entities\Area;
use Symfony\Component\HttpFoundation\Response;

final class EpRegister extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(): Responsable|Response
    {
        return (new TkInertia('Auth::Register', ['areas' => Area::allOrCached()]))->act();
    }
}
