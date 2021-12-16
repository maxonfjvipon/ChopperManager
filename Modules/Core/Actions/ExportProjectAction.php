<?php


namespace Modules\Core\Actions;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\ExportProjectRequest;
use Modules\Core\Support\Rates;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Traits\ConstructsSelectionCurves;
use VerumConsilium\Browsershot\Facades\PDF;

class ExportProjectAction
{
    use ConstructsSelectionCurves;

    public function execute(Project $project, ExportProjectRequest $request): Response
    {
        $rates = new Rates(Auth::user()->currency->code);

        $project->load(['selections' => function ($query) use ($request) {
            $query->whereIn('id', $request->selection_ids);
        },
            'selections.pump',
            'selections.pump.series',
            'selections.pump.series.category',
            'selections.pump.series.power_adjustment',
            'selections.pump.series.discount',
            'selections.pump.brand',
            'selections.pump.connection_type',
            'selections.pump.price_list',
            'selections.pump.price_list.currency',
        ]);
        // TODO: add get and write concrete fields
        $project->selections->transform(function (Selection $selection) use ($rates) {
            $selection->{'curves_data'} = $this->selectionCurvesData($selection);

            $pump_price_list = null;
            $pump_price = null;
            $discounted_pump_price = null;
            if ($selection->pump->price_list) {
                $pump_price_list = $selection->pump->price_list;
                $pump_price = $pump_price_list->currency->code === $rates->base()
                    ? $pump_price_list->price
                    : round($pump_price_list->price / $rates->rate($pump_price_list->currency->code), 2);
                $discount = $selection->pump->series->discount
                    ? $selection->pump->series->discount->value
                    : 0;
                $discounted_pump_price = $pump_price - $pump_price * $discount / 100;
            }

            $selection->{'discounted_price'} = round($discounted_pump_price, 1) ?? null;
            $selection->{'total_discounted_price'} = round($discounted_pump_price * $selection->pumps_count, 1) ?? null;
            $selection->{'retail_price'} = round($pump_price, 1) ?? null;
            $selection->{'total_retail_price'} = round($pump_price * $selection->pumps_count, 1) ?? null;

            return $selection;
        });
        return PDF::loadHtml(view('core::project_export', [
            'project' => $project,
            'request' => $request,
        ])->render())->download();
    }
}
