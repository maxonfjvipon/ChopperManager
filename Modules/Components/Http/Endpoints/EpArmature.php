<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Components\Actions\AcArmature;
use Modules\Components\Actions\AcCollectors;
use Symfony\Component\HttpFoundation\Response;

final class EpArmature extends Controller
{
    /**
     * @param AcArmature $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(AcArmature $action): Responsable|Response
    {
        return (new TkInertia("Components::Armature", $action()))->act();
    }
}
