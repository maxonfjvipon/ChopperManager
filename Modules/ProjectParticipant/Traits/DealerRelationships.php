<?php

namespace Modules\ProjectParticipant\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Project\Entities\Project;
use Modules\PumpSeries\Entities\PumpSeries;

/**
 * Dealer relationships.
 */
trait DealerRelationships
{
    /**
     * Available series for pump-manager users.
     * @return BelongsToMany
     */
    public function available_series(): BelongsToMany
    {
        return $this->belongsToMany(PumpSeries::class, 'dealers_pump_series', 'dealer_id', 'series_id');
    }

    /**
     * Project with the dealer.
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'dealer_id', 'id');
    }
}
