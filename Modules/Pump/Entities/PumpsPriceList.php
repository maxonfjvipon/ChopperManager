<?php

namespace Modules\Pump\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Entities\Currency;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Pumps price list
 * @property Currency $currency
 * @property float $price
 */
final class PumpsPriceList extends Model
{
    use HasFactory, HasCompositePrimaryKey, UsesTenantConnection;

    public $timestamps = false;
    protected $guarded = ['id'];
    public $incrementing = false;
    protected $primaryKey = ['pump_id', 'country_id'];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
