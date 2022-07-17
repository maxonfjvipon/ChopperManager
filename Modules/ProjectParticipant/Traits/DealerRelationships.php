<?php

namespace Modules\ProjectParticipant\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Project\Entities\Project;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\ProjectParticipant\Entities\DealerMarkup;
use Modules\PumpSeries\Entities\PumpSeries;

/**
 * Dealer relationships.
 */
trait DealerRelationships
{
    /**
     * Available series for pump-manager users.
     */
    public function available_series(): BelongsToMany
    {
        return $this->belongsToMany(PumpSeries::class, 'dealers_pump_series', 'dealer_id', 'series_id');
    }

    /**
     * Project with the dealer.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'dealer_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function markups(): HasMany
    {
        return $this->hasMany(DealerMarkup::class, 'dealer_id', 'id');
    }
}
