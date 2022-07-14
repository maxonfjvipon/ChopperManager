<?php

namespace Modules\Selection\Actions;

use App\Support\Rates\RealRates;
use App\Support\Rates\StickyRates;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Numerable\Rounded;
use Modules\Selection\Entities\PumpStation;
use Modules\Selection\Http\Requests\RqDetermineSelection;
use Modules\Selection\Support\ArrCostStructure;
use Modules\Selection\Support\PumpStationPrice;

/**
 * Update pump station cost action.
 */
final class AcUpdatePumpStationCost extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @param  RqDetermineSelection  $request
     *
     * @throws Exception
     */
    public function __construct(private RqDetermineSelection $request)
    {
        parent::__construct(
            new ArrObject(
                'cost_price',
                new Rounded(
                    new PumpStationPrice(
                        new ArrCostStructure(
                            $this->request,
                            new StickyRates(new RealRates()),
                            [
                                'pump' => ($pumpStation = PumpStation::find($this->request->pump_station))->pump,
                                'control_system' => $pumpStation->control_system,
                                'chassis' => $pumpStation->chassis,
                                'collectors' => collect([
                                    $pumpStation->input_collector,
                                    $pumpStation->output_collector,
                                ]),
                            ],
                            $pumpStation->pumps_count
                        )
                    ),
                    2
                )
            )
        );
    }
}
