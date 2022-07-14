<?php

namespace Modules\ProjectParticipant\Actions;

use App\Interfaces\InvokableAction;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\ProjectParticipant\Entities\DealerPumpSeries;
use Modules\ProjectParticipant\Http\Requests\RqStoreDealer;

/**
 * Store dealer action.
 */
final class AcStoreDealer implements InvokableAction
{
    /**
     * Ctor.
     */
    public function __construct(private RqStoreDealer $request)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(): void
    {
        DealerPumpSeries::updateSeriesForDealer(
            $this->request->available_series_ids ?? [],
            Dealer::create($this->request->dealerProps())
        );
    }
}
