<?php

namespace Modules\Selection\Support\SelectedPumps;

use App\Interfaces\Rates;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Maxonfjvipon\Elegant_Elephant\Logical\Conjunction;
use Maxonfjvipon\Elegant_Elephant\Logical\KeyExists;
use Maxonfjvipon\Elegant_Elephant\Logical\StrContains;
use Maxonfjvipon\Elegant_Elephant\Numerable\Rounded;
use Maxonfjvipon\Elegant_Elephant\Text\TxtSticky;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Support\ArrCostStructure;
use Modules\Selection\Support\PumpStationPrice;
use Modules\Selection\Support\TxtCostStructure;
use Modules\Selection\Support\TxtPumpStationFullName;
use Modules\Selection\Support\TxtPumpStationName;

/**
 * Selected pump.
 */
final class ArrSelectedPump extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @throws Exception
     */
    public function __construct(
        int &$key,
        RqMakeSelection $request,
        int $mainPumpsCount,
        Rates $rates,
        array $components,
        Dealer $dealer
    ) {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    'name',
                    $name = new TxtSticky(
                        new TxtPumpStationName(
                            $controlSystem = $components['control_system'],
                            $pumpsCount = $mainPumpsCount + $request->reserve_pumps_count,
                            $pump = $components['pump'],
                            $inputCollector = ($collectors = $components['collectors'])->firstWhere('dn_pipes', $pump->dn_suction),
                            $components['jockey_pump'] ?? null
                        )
                    )
                ),
                new ArrObject(
                    'full_name',
                    new TxtPumpStationFullName(
                        $name,
                        $controlSystem
                    )
                ),
                new ArrObject(
                    'cost_price',
                    new Rounded(
                        new PumpStationPrice(
                            $costStructure = new ArrSticky(
                                new ArrCostStructure(
                                    $request,
                                    $rates,
                                    $components,
                                    $pumpsCount,
                                )
                            ),
                            $dealer
                        ),
                        2
                    )
                ),
                new ArrIf(
                    \Auth::user()->dealer_id === Dealer::BPE,
                    fn () => new ArrObject(
                        'cost_structure',
                        new TxtCostStructure($costStructure)
                    ),
                ),
                new ArrObject(
                    'bad',
                    new StrContains($name, '?')
                ),
                [
                    'key' => $key++,
                    'created_at' => formatted_date(now()),
                    'pump_article' => $pump->article,
                    'power' => $pump->power,
                    'total_power' => $pump->power * $pumpsCount,
                    'control_system_article' => $controlSystem?->article,

                    'control_system_id' => $controlSystem?->id,
                    'input_collector_id' => $inputCollector?->id,
                    'output_collector_id' => $collectors->firstWhere('dn_pipes', $pump->dn_pressure)?->id,
                    'chassis_id' => $components['chassis']?->id,
                    'pump_id' => $pump->id,

                    'flow' => $request->flow,
                    'head' => $request->head,
                    'reserve_pumps_count' => $request->reserve_pumps_count,
                    'main_pumps_count' => $mainPumpsCount,
                ],
                new ArrIf(
                    new Conjunction(
                        new KeyExists('jockey_pump', $components),
                        new KeyExists('jockey_chassis', $components)
                    ),
                    fn () => [
                        'jockey_pump_id' => $components['jockey_pump']?->id,
                        'jockey_chassis_id' => $components['jockey_chassis']?->id,
                        'jockey_flow' => $request->jockey_flow,
                        'jockey_head' => $request->jockey_head,
                    ]
                )
            )
        );
    }
}
