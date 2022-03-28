<?php

namespace App\Models;

use App\Traits\HasCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Control system.
 */
class ControlSystem extends Model
{
    use HasFactory, HasCurrency, SoftDeletes;

    public $timestamps = false;
    protected $guarded = [];

    public function montage_type(): BelongsTo
    {
        return $this->belongsTo(MontageType::class, 'montage_type_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ControlSystemType::class, 'type_id');
    }
}
