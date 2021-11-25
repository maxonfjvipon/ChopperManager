<?php


namespace Modules\Core\Actions;


use Illuminate\Http\Response;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\ExportProjectRequest;
use Modules\Selection\Actions\MakeSelectionCurvesAction;
use VerumConsilium\Browsershot\Facades\PDF;

class ExportProjectAction
{
    public function execute(Project $project, ExportProjectRequest $request): Response
    {
        $project->load(['selections' => function ($query) use ($request) {
            $query->whereIn('id', $request->selection_ids)
                ->select('id', 'pump_id', 'project_id', 'reserve_pumps_count', 'pumps_count', 'flow', 'head', 'selected_pump_name');
        }, 'selections.pump', 'selections.pump.series', 'selections.pump.brand']);
        $project->selections->transform(function ($selection) {
            $selection->{'curves_data'} = (new MakeSelectionCurvesAction())
                ->selectionPerfCurvesData(
                    $selection->pump,
                    $selection->pumps_count - $selection->reserve_pumps_count,
                    $selection->pumps_count,
                    $selection->flow,
                    $selection->head
                );
            return $selection;
        });
        return PDF::loadHtml(view('core::project_export', [
            'project' => $project,
            'request' => $request,
        ])->render())->inline();
    }
}
