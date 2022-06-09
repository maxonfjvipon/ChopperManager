<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Components\Actions\AcControlSystems;
use Symfony\Component\HttpFoundation\Response;

final class EpControlSystems extends Controller
{
    /**
     * @param AcControlSystems $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(AcControlSystems $action): Responsable|Response
    {
        return (new TkInertia('Components::ControlSystems', $action()))->act();
    }
}
