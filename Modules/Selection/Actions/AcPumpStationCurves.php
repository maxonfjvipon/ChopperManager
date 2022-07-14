<?php

namespace Modules\Selection\Actions;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Selection\Entities\PumpStation;
use Modules\Selection\Http\Requests\RqPumpStationCurves;
use Modules\Selection\Support\PumpStationCurves;

/**
 * Pump station curves action.
 */
final class AcPumpStationCurves extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct(RqPumpStationCurves $request)
    {
        parent::__construct(
            new ArrMerged(
                new PumpStationCurves(
                    new PumpStation($request->mainStationProps()),
                ),
                new ArrIf(
                    $request->hasJockey(),
                    fn () => new PumpStationCurves(
                        new PumpStation($request->jockeyPumpProps()),
                        'jockey_curves'
                    )
                )
            )
        );
    }
}
