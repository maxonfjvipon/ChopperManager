<?php

namespace Modules\Core\Endpoints;

use App\Http\Controllers\Controller;
use App\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects statistics endpoint.
 * @package Modules\Core\Endpoints
 */
class ProjectsStatisticsEndpoint extends Controller implements Renderable
{
    /**
     * @return Responsable|Response
     */
    public function __invoke(): Responsable|Response
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function render(Request $request = null): Responsable|Response
    {
        // TODO: Implement render() method.
    }
}
