<?php


namespace Modules\Selection\Services\PumpType;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\Pure;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;
use Modules\Selection\Http\Requests\ExportAtOnceSelectionRequest;
use Modules\Selection\Http\Requests\MakeSelectionRequest;
use Modules\Selection\Http\Requests\SinglePump\MakeSinglePumpSelectionRequest;
use Modules\Selection\Transformers\SelectionResources\DoublePumpSelectionResource;

class DoublePumpSelectionService extends PumpableTypeSelectionService
{
    /**
     * DoublePumpSelectionService constructor.
     */
    #[Pure] public function __construct()
    {
        parent::__construct(
            'selection::selection_to_export',
            'selection::selection_perf_curves'
        );
    }

    /**
     * @return string
     */
    public function createPath(): string
    {
        return 'Selection::DoublePump';
    }

    /**
     * @param Selection $selection
     * @return JsonResource
     */
    #[Pure] public function selectionResource(Selection $selection): JsonResource
    {
        return new DoublePumpSelectionResource($selection);
    }

    /**
     * @param MakeSelectionRequest $request
     * @return JsonResponse
     */
    public function select(MakeSelectionRequest $request): JsonResponse
    {
    }

    /**
     * @param Selection $selection
     * @return array
     */
    protected function selectionCurvesData(Selection $selection): array
    {
        // TODO: Implement selectionCurvesData() method.
    }

    /**
     * @param CurvesForSelectionRequest $request
     * @return Selection
     */
    protected function selectionWithParamsFromRequest(CurvesForSelectionRequest $request): Selection
    {
        // TODO: Implement selectionWithParamsFromRequest() method.
    }

    /**
     * @return array
     */
    protected function dbPumpsGetProps(): array
    {
        // TODO: Implement dbPumpsGetProps() method.
    }

    /**
     * @param MakeSelectionRequest $request
     * @return Closure
     */
    function pumpableCoefficientsClosure(MakeSelectionRequest $request): Closure
    {
        // TODO: Implement pumpableCoefficientsClosure() method.
    }

    /**
     * @param ExportAtOnceSelectionRequest $request
     * @return Selection
     */
    protected function selectionForExportAtOnceFromRequest(ExportAtOnceSelectionRequest $request): Selection
    {
        // TODO: Implement selectionForExportAtOnceFromRequest() method.
    }
}
