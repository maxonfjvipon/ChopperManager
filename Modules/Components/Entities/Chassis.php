<?php

namespace Modules\Components\Entities;

use App\Models\Enums\Currency;
use App\Traits\Cached;
use App\Traits\HasPriceByRates;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pump\Entities\Pump;

/**
 * Chassis.
 *
 * @property int $id
 * @property string $article
 * @property int $pumps_count
 * @property int $pumps_weight
 * @property int $weight
 * @property float $price
 * @property Currency $currency
 * @property Carbon $price_updated_at
 */
final class Chassis extends Model
{
    use HasFactory, SoftDeletes, Cached;
    use HasPriceByRates;

    public $timestamps = false;
    protected $table = "chassis";
    protected $guarded = [];

    protected $casts = [
        'currency' => Currency::class,
        'price_updated_at' => 'datetime:d.m.Y',
    ];

    /**
     * @return string
     */
    protected static function getCacheKey(): string
    {
        return "chassis";
    }

    /**
     * @throws Exception
     */
    public static function appropriateFor(Pump $pump, int $pumpsCount): ?self
    {
        return Chassis::allOrCached()
            ->where('pumps_count', $pumpsCount)
            ->where('pumps_weight', '>=', $pump->weight * $pumpsCount)
            ->sortBy('pumps_weight')
            ->first();
    }
}
