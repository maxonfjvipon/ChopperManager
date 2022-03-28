<?php

namespace App\Traits;

use App\Models\ConnectionType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasConnectionType
{
    public function connection_type(): BelongsTo
    {
        return $this->belongsTo(ConnectionType::class, 'connection_type_id');
    }
}
