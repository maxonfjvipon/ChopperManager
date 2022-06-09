<?php

namespace Modules\Selection\Http\Endpoints;

use App\Takes\TkAuthorize;
use App\Takes\TkInertia;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Selection\Actions\AcCreateSelection;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\RqCreateSelection;
use Modules\Selection\Takes\TkOptAuthorizedProject;
use Modules\Selection\Support\TxtCreateSelectionComponent;
use Modules\Selection\Tests\Feature\SelectionEndpointsTest;
use Modules\Selection\Transformers\SelectionPropsResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Create selection endpoint.
 * @see SelectionEndpointsTest::test_selections_create_endpoint()
 * @package Modules\Selection\Takes
 */
final class EpCreateSelection extends Controller
{
    /**
     * @param int $project_id
     * @param string $stationType
     * @param string $selectionType
     * @param AcCreateSelection $action
     * @return Responsable|Response
     * @throws Exception
     */
    public function __invoke(
        int $project_id,
        string $stationType,
        string $selectionType,
        AcCreateSelection $action
    ): Responsable|Response
    {
        return (new TkInertia(
            new TxtCreateSelectionComponent($stationType, $selectionType),
            $action($project_id, $stationType, $selectionType)
        ))->act();
    }
}
