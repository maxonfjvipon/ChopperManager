<?php

namespace App\Traits;

use App\Models\Area;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Has {@see Area}
 */
trait HasArea
{
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
