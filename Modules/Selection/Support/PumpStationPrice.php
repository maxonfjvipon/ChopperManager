<?php

namespace Modules\Selection\Support;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Modules\ProjectParticipant\Entities\Dealer;

/**
 * Pump station price.
 */
final class PumpStationPrice implements Numerable
{
    /**
     * Ctor.
     *
     * @param  Arrayable  $components
     * @param  Dealer  $dealer
     */
    public function __construct(private Arrayable $components, private Dealer $dealer)
    {
    }

    /**
     * @return float|int
     *
     * @throws Exception
     */
    public function asNumber(): float|int
    {
        $sum = 0;
        $components = $this->components->asArray();
        foreach ($components as $component => $price) {
            if ('pump' === $component) {
                continue;
            }
            if (is_null($price)) {
                return 999999999;
            }
            $sum += $price;
        }

        // with markups
        if (\Auth::user()->dealer_id === $this->dealer->id) {
            $toMarkup = $sum;
            if ($this->dealer->without_pumps) {
                $toMarkup = $sum - $components['pumps'];
            }
            if ($markup = $this->dealer->markupForPrice($toMarkup)) {
                return $sum + $toMarkup * $markup->value / 100;
            }
        }

        return $sum;
    }
}
