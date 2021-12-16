<?php


namespace Modules\Selection\Traits;

use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;

trait ConstructsSelectionCurvesFromRequest
{
    use ConstructsSelectionCurves;

    abstract protected function selectionWithParamsFromRequest(CurvesForSelectionRequest $request): Selection;

    protected function selectionCurvesDataFromRequest(CurvesForSelectionRequest $request): array
    {
        return $this->selectionCurvesData($this->selectionWithParamsFromRequest($request));
    }
}
