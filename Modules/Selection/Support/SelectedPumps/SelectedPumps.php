<?php

namespace Modules\Selection\Support\SelectedPumps;

use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Logical\EqualityOf;
use Maxonfjvipon\Elegant_Elephant\Numerable\LengthOf;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Http\Requests\MakeSelectionRequest;

final class SelectedPumps implements Arrayable
{
    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * @param MakeSelectionRequest $req
     */
    public function __construct(MakeSelectionRequest $req)
    {
        $this->request = $req;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $selectedPumps = (match ($this->request->pumpable_type) {
            Pump::$SINGLE_PUMP => new SelectedSinglePumps($this->request),
            Pump::$DOUBLE_PUMP => new SelectedDoublePumps($this->request)
        })->asArray();
        return (new EqualityOf(new LengthOf($selectedPumps), 0))->asBool()
            ? ['info' => __('flash.selections.pumps_not_found')]
            : [
                'selected_pumps' => $selectedPumps,
                'working_point' => [
                    'x' => $this->request->flow,
                    'y' => $this->request->head
                ]
            ];
    }
}
