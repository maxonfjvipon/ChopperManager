<?php

namespace Modules\Selection\Support;

use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Http\Requests\RqMakeSelection;

/**
 * Pumps for select from
 * @package Modules\Selection\Support
 */
final class ArrPumpsForSelecting implements Arrayable
{
    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * Ctor.
     * @param RqMakeSelection $req
     */
    public function __construct(RqMakeSelection $req)
    {
        $this->request = $req;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return Pump::whereIn('series_id', $this->request->pump_series_ids)
            ->whereIsDiscontinued(false)
            ->with([
                'series',
                'series.brand',
                'coefficients' => function ($query) {
                    $query->whereBetween(
                        'position',
                        [1, max($this->request->main_pumps_counts) + $this->request->reserve_pumps_count]
                    );
                }
            ])
            ->get()
            ->all();
    }
}
