<?php

namespace App\Models;

use App\Traits\HasConnectionType;
use App\Traits\HasCurrency;
use App\Traits\HasDN;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Armature.
 */
class Armature extends Model
{
    use HasFactory, HasDN, HasCurrency, HasConnectionType, SoftDeletes;

    protected $guarded = [];
    protected $table = "armature";
    public $timestamps = false;

    public function type(): BelongsTo
    {
        return $this->belongsTo(ArmatureType::class, 'type_id');
    }
}
