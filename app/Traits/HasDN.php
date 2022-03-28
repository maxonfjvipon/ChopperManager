<?php

namespace App\Traits;

use App\Models\DN;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Has {@see DN}
 */
trait HasDN
{
    public function dn(): HasOne
    {
        return $this->hasOne(DN::class, 'dn_id');
    }

}
