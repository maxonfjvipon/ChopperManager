<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Components\Actions\AcChassis;
use Symfony\Component\HttpFoundation\Response;

final class EpChassis extends Controller
{
    /**
     * @param AcChassis $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(AcChassis $action): Responsable|Response
    {
        return (new TkInertia('Components::Chassis', $action()))->act();
    }
}
