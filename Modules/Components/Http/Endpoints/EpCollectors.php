<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Components\Actions\AcCollectors;
use Symfony\Component\HttpFoundation\Response;

final class EpCollectors extends Controller
{
    /**
     * @param AcCollectors $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(AcCollectors $action): Responsable|Response
    {
        return (new TkInertia('Components::Collectors', $action()))->act();
    }
}
