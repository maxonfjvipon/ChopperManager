<?php

namespace Modules\Selection\Support\SelectedPumps;

use App\Interfaces\Rates;
use Illuminate\Database\Eloquent\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Components\Entities\Chassis;
use Modules\Components\Entities\Collector;
use Modules\Components\Entities\ControlSystem;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Support\ArrControlSystemForSelection;

final class SelectedPumpsAFHandle extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(
        private RqMakeSelection $request,
        private Rates $rates,
        private Collection $controlSystems
    ) {
        parent::__construct(
            new ArrFromCallback(
                function () {
                    $key = 1;
                    $chassis = Chassis::appropriateFor($pump = Pump::find($this->request->pump_id)
                        ->load([
                            'series',
                            'series.brand',
                            'coefficients' => fn ($query) => $query->whereBetween(
                                'position',
                                [1, $this->request->main_pumps_count + $this->request->reserve_pumps_count]
                            ),
                        ]),
                        $pumpsCount = $this->request->main_pumps_count + $this->request->reserve_pumps_count
                    );
                    $collectors = Collector::forSelection(
                        $this->request->station_type,
                        $pump,
                        $pumpsCount,
                        [
                            'dn' => ($explodedDnMaterial = explode(' ', $this->request->collector))[0],
                            'material' => $explodedDnMaterial[1],
                        ],
                        DN::minDNforPump($pump)
                    );
                    $jockeyPump = null;
                    $jockeyChassis = null;
                    if ($isSprinkler = $this->request->jockey_flow && $this->request->jockey_head && $this->request->jockey_pump_id) {
                        $jockeyPump = Pump::find($this->request->jockey_pump_id);
                        $jockeyChassis = Chassis::appropriateFor($jockeyPump, 1);
                    }

                    return new ArrMapped(
                        new ArrControlSystemForSelection($this->request, $pump, $pumpsCount, $isSprinkler, $this->controlSystems),
                        function (?ControlSystem $controlSystem) use ($pump, $collectors, $chassis, &$key, $jockeyPump, $jockeyChassis) {
                            return new ArrSelectedPump(
                                $key,
                                $this->request,
                                $this->request->main_pumps_count,
                                $this->rates,
                                [
                                    'pump' => $pump,
                                    'control_system' => $controlSystem,
                                    'chassis' => $chassis,
                                    'collectors' => $collectors,
                                    'jockey_pump' => $jockeyPump,
                                    'jockey_chassis' => $jockeyChassis,
                                ]
                            );
                        },
                        true
                    );
                }
            )
        );
    }
}
