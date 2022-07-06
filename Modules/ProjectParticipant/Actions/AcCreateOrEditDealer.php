<?php

namespace Modules\ProjectParticipant\Actions;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\ProjectParticipant\Transformers\RcDealerToEdit;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\User\Entities\Area;

/**
 * Create or edit dealer action.
 */
final class AcCreateOrEditDealer extends ArrEnvelope
{
    /**
     * Ctor.
     * @param Dealer|null $dealer
     * @throws Exception
     */
    public function __construct(?Dealer $dealer)
    {
        parent::__construct(
            new ArrMerged(
                [
                    'filter_data' => [
                        'areas' => Area::allOrCached(),
                        'series' => PumpSeries::with('brand')
                            ->get()
                            ->map(fn(PumpSeries $series) => [
                                'id' => $series->id,
                                'name' => $series->brand->name . ' ' . $series->name
                            ])
                            ->all()
                    ]
                ],
                new ArrIf(
                    !!$dealer,
                    fn() => ['dealer' => new RcDealerToEdit($dealer)]
                )
            )
        );
    }
}
