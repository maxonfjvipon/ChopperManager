<?php

namespace Modules\Selection\Actions;

use Illuminate\Http\Response;
use Modules\Selection\Entities\SinglePumpSelection;
use Modules\Selection\Http\Requests\ExportSinglePumpSelectionRequest;

class ExportAtOnceSinglePumpSelectionAction
{
    public function execute(ExportSinglePumpSelectionRequest $request): Response
    {
        $selection = new SinglePumpSelection;
        $selection->selected_pump_name = $request->selected_pump_name;
        $selection->pump_id = $request->pump_id;
        $selection->pumps_count = $request->pumps_count;
        $selection->reserve_pumps_count = $request->reserve_pumps_count;
        $selection->flow = $request->flow;
        $selection->head = $request->head;
        $selection->fluid_temperature = $request->fluid_temperature;
        return (new ExportSinglePumpSelectionAction())->execute($request, $selection);
    }
}
