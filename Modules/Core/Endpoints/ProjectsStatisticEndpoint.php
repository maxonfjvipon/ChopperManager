<?php

namespace Modules\Core\Endpoints;

use App\Http\Controllers\Controller;
use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects statistics endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsStatisticEndpoint extends Controller
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {

    }
}
