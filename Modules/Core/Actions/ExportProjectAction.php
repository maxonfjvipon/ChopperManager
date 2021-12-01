<?php


namespace Modules\Core\Actions;


use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Requests\ExportProjectRequest;
use Modules\Core\Support\Rates;
use Modules\Selection\Actions\MakeSelectionCurvesAction;
use VerumConsilium\Browsershot\Facades\PDF;

class ExportProjectAction
{
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
            'selections.pump.series.discounts' => function ($query) {
                $query->where('user_id', Auth::id());
            },
            'selections.pump.brand',
            'selections.pump.brand.discounts' => function ($query) {
                $query->where('user_id', Auth::id());
            },
            'selections.pump.connection_type',
            'selections.pump.price_lists' => function($query) {
                $query->where('country_id', Auth::user()->country_id);
            },
            'selections.pump.price_lists.currency',
        ]);
        $project->selections->transform(function ($selection) use ($rates) {
            $selection->{'curves_data'} = (new MakeSelectionCurvesAction())
                ->selectionPerfCurvesData(
                    $selection->pump,
                    $selection->pumps_count - $selection->reserve_pumps_count,
                    $selection->pumps_count,
                    $selection->flow,
                    $selection->head
                );

            $pump_price_list = null;
            $pump_price = null;
            $discounted_pump_price = null;
            if (count($selection->pump->price_lists) === 1) {
                $pump_price_list = $selection->pump->price_lists[0];
                $pump_price = $pump_price_list->currency->code === $rates->base()
                    ? $pump_price_list->price
                    : round($pump_price_list->price / $rates->rate($pump_price_list->currency->code), 2);
                $discount = count($selection->pump->series->discounts) == 1
                    ? $selection->pump->series->discounts[0]->value
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
        ])->render())->inline();
    }
}
