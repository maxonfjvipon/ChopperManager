<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Entities\Area;

/**
 * Has {@see Area}.
 */
trait HasArea
{
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
