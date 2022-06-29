<?php

namespace Modules\Selection\Actions;

use App\Support\Rates\RealRates;
use App\Support\Rates\StickyRates;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
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
        parent::__construct(
            new ArrFromCallback(
                function () use ($request) {
                    $controlSystems = ControlSystem::allOrCached()->load('type');
                    $rates = new StickyRates(new RealRates());
                    return new ArrTernary(
                        new IsEmpty(
                            $selectedPumps = new ArrSticky(
                                match ($request->station_type) {
                                    StationType::getKey(StationType::WS) => match ($request->selection_type) {
                                        SelectionType::getKey(SelectionType::Auto) => new SelectedPumpsWSAuto(
                                            $request,
                                            new ArrDNMaterials($request),
                                            $rates,
                                            $controlSystems
                                        ),
                                        SelectionType::getKey(SelectionType::Handle) => new SelectedPumpsWSHandle(
                                            $request,
                                            $rates,
                                            $controlSystems
                                        )
                                    },
                                    StationType::getKey(StationType::AF) => match ($request->selection_type) {
                                        SelectionType::getKey(SelectionType::Auto) => new SelectedPumpsAFAuto(
                                            $request,
                                            new ArrDNMaterials($request),
                                            $rates,
                                            $controlSystems
                                        ),
                                        SelectionType::getKey(SelectionType::Handle) => new SelectedPumpsAFHandle(
                                            $request,
                                            $rates,
                                            $controlSystems
                                        ),
                                    }
                                }
                            )
                        ),
                        ['info' => __('flash.selections.pumps_not_found')],
                        fn() => new ArrMerged(
                            ['working_point' => [
                                'x' => $request->flow,
                                'y' => $request->head
                            ]],
                            new ArrObject(
                                "selected_pumps",
                                $selectedPumps
                            )
                        )
                    );
                }
            )
        );
    }
}
