<?php

namespace App\Models;

use App\Traits\HasConnectionType;
use App\Traits\HasCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Collector.
 */
class Collector extends Model
{
    use HasFactory, HasCurrency, HasConnectionType, SoftDeletes;

    protected $guarded = [];
    public $timestamps = false;

    public function dn_common(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_common_id');
    }

    public function dn_pipes(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_pipes_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(CollectorMaterial::class, 'material_id');
    }
}
