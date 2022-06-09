<?php

namespace Modules\Selection\Support\SelectedPumps;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrTernary;
use Maxonfjvipon\Elegant_Elephant\Logical\EqualityOf;
use Maxonfjvipon\Elegant_Elephant\Numerable\LengthOf;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;
use Modules\Selection\Http\Requests\RqMakeSelection;

final class SelectedPumps implements Arrayable
{
    /**
     * @param RqMakeSelection $request
     */
    public function __construct(private RqMakeSelection $request)
    {
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $dnMaterials = new ArrSticky(
            new ArrMapped(
                $this->request->collectors,
                function (string $dnMaterial) {
                    $exploded = explode(" ", $dnMaterial);
                    return [
                        'dn' => $exploded[0],
                        'material' => $exploded[1]
                    ];
                },
            )
        );
        return (new ArrTernary(
            new EqualityOf(
                new LengthOf(
                    $selectedPumps = new ArrSticky(
                        match ($this->request->station_type) {
                            StationType::getKey(StationType::Water) => match($this->request->selection_type) {
                                SelectionType::getKey(SelectionType::Auto) => new SelectedPumpsWaterAuto($this->request, $dnMaterials),
                                SelectionType::getKey(SelectionType::Handle) => new SelectedPumpsWaterAuto($this->request, $dnMaterials)
                            },
//                            StationType::getKey(StationType::Fire) => match ($this->request->selection_type) {
//
//                            }
                        }
                    )
                ),
                0),
            ['info' => __('flash.selections.pumps_not_found')],
            new ArrFromCallback(
                fn() => [
                    'selected_pumps' => $selectedPumps->asArray(),
                    'working_point' => [
                        'x' => $this->request->flow,
                        'y' => $this->request->head
                    ]
                ]
            )
        ))->asArray();
    }
}
