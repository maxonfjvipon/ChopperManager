<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PumpsPriceList extends Model
{
    use HasFactory, HasCompositePrimaryKey;

    public $timestamps = false;
    protected $guarded = ['id'];
    public $incrementing = false;
    protected $primaryKey = ['pump_id', 'country_id'];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
