<?php

namespace Modules\Selection\Actions;

use App\Interfaces\Rates;
use App\Support\Rates\RealRates;
use App\Support\Rates\StickyRates;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrTernary;
use Maxonfjvipon\Elegant_Elephant\Logical\IsEmpty;
use Modules\Components\Entities\ControlSystem;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Support\ArrDNMaterials;
use Modules\Selection\Support\SelectedPumps\SelectedPumpsAFAuto;
use Modules\Selection\Support\SelectedPumps\SelectedPumpsAFHandle;
use Modules\Selection\Support\SelectedPumps\SelectedPumpsWSAuto;
use Modules\Selection\Support\SelectedPumps\SelectedPumpsWSHandle;

/**
 * Make selection action.
 */
final class AcMakeSelection extends ArrEnvelope
{
    /**
     * Ctor.
     *
     * @param RqMakeSelection $request
     * @throws Exception
     */
    public function __construct(RqMakeSelection $request)
    {
        $controlSystems = ControlSystem::allOrCached()->load('type');
        parent::__construct(
            new ArrTernary(
                new IsEmpty(
                    $selectedPumps = new ArrSticky(
                        match ($request->station_type) {
                            StationType::getKey(StationType::WS) => match ($request->selection_type) {
                                SelectionType::getKey(SelectionType::Auto) => new SelectedPumpsWSAuto(
                                    $request,
                                    new ArrDNMaterials($request),
                                    self::getRates(),
                                    $controlSystems
                                ),
                                SelectionType::getKey(SelectionType::Handle) => new SelectedPumpsWSHandle(
                                    $request,
                                    self::getRates(),
                                    $controlSystems
                                )
                            },
                            StationType::getKey(StationType::AF) => match ($request->selection_type) {
                                SelectionType::getKey(SelectionType::Auto) => new SelectedPumpsAFAuto(
                                    $request,
                                    new ArrDNMaterials($request),
                                    self::getRates(),
                                    $controlSystems
                                ),
                                SelectionType::getKey(SelectionType::Handle) => new SelectedPumpsAFHandle(
                                    $request,
                                    self::getRates(),
                                    $controlSystems
                                ),
                            }
                        }
                    )
                ),
                ['info' => __('flash.selections.pumps_not_found')],
                fn() => [
                    'selected_pumps' => $selectedPumps->asArray(),
                    'working_point' => [
                        'x' => $request->flow,
                        'y' => $request->head
                    ]
                ]
            )
        );
    }

    /**
     * Get rates.
     * @return Rates
     */
    private static function getRates(): Rates
    {
        return new StickyRates(new RealRates());
    }
}
