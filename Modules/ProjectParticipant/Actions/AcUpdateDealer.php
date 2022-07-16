<?php

namespace Modules\ProjectParticipant\Actions;

use App\Interfaces\InvokableAction;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\ProjectParticipant\Entities\DealerPumpSeries;
use Modules\ProjectParticipant\Http\Requests\RqUpdateDealer;

/**
 * Update dealer action.
 */
final class AcUpdateDealer implements InvokableAction
{
    /**
     * Ctor.
     */
    public function __construct(private RqUpdateDealer $request)
    {
    }

    public function __invoke(): void
    {
        ($dealer = Dealer::find($this->request->dealer))->update($this->request->dealerProps());
        DealerPumpSeries::updateSeriesForDealer($this->request->available_series_ids, $dealer);
    }
}
