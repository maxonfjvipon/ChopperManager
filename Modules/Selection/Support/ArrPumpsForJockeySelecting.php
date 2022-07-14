<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Pump\Entities\Pump;

/**
 * Pumps for auto jockey pump selection.
 */
final class ArrPumpsForJockeySelecting implements Arrayable
{
    /**
     * @param array $seriesIds
     */
    public function __construct(private array $seriesIds)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function asArray(): array
    {
        return Pump::whereIn('series_id', $this->seriesIds)
            ->whereIsDiscontinued(false)
            ->with([
                'series',
                'series.brand',
                'coefficients' => fn ($query) => $query->where('position', 1),
            ])
            ->get()
            ->all();
    }
}
