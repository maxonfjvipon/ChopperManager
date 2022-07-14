<?php

namespace Modules\Pump\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Pump\Entities\Pump;

/**
 * Has jockey pump relationship.
 */
trait HasJockeyPump
{
    public function jockey_pump(): BelongsTo
    {
        return $this->belongsTo(Pump::class, 'jockey_pump_id');
    }
}
