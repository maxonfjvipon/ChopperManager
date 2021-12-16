<?php

namespace Modules\Selection\Contracts;

use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\CurvesForSelectionRequest;
use Modules\Selection\Http\Requests\ExportAtOnceSelectionRequest;
use Modules\Selection\Http\Requests\ExportSelectionRequest;
use Modules\Selection\Http\Requests\MakeSelectionRequest;

interface ManageSelectionContract
{
    public function select(MakeSelectionRequest $request);

    public function export(ExportSelectionRequest $request, Selection $selection);

    public function curves(CurvesForSelectionRequest $request);

    public function exportAtOnce(ExportAtOnceSelectionRequest $request);
}
