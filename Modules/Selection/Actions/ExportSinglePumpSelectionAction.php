<?php


namespace Modules\Selection\Actions;

use Illuminate\Http\Response;
use Modules\Selection\Entities\SinglePumpSelection;
use Modules\Selection\Http\Requests\ExportSinglePumpSelectionRequest;
use VerumConsilium\Browsershot\Facades\PDF;

class ExportSinglePumpSelectionAction
{
    public function execute(ExportSinglePumpSelectionRequest $request, SinglePumpSelection $selection): Response
    {
        $selection->load([
            'pump',
            'pump.connection_type',
            'pump.series',
            'pump.series.category',
            'pump.series.power_adjustment',
            'pump.brand'
        ]);
        $selection->{'curves_data'} = (new MakeSelectionCurvesAction())
            ->selectionPerfCurvesData(
                $selection->pump,
                $selection->pumps_count - $selection->reserve_pumps_count,
                $selection->pumps_count,
                $selection->flow,
                $selection->head
            );
        return PDF::loadHtml(view('selection::selection_to_export', [
            'selection' => $selection,
            'request' => $request,
        ])->render())->download();
    }
}
