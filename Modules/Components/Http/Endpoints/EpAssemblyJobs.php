<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Takes\TkInertia;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Modules\Components\Actions\AcAssemblyJobs;
use Symfony\Component\HttpFoundation\Response;

final class EpAssemblyJobs extends Controller
{
    /**
     * @param AcAssemblyJobs $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(AcAssemblyJobs $action): Responsable|Response
    {
        return (new TkInertia('Components::AssemblyJobs', $action()))->act();    }
}
