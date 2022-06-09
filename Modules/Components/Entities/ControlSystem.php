<?php

namespace Modules\Components\Entities;

use App\Models\Enums\Currency;

use App\Traits\Cached;
use App\Traits\HasPriceByRates;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Selection\Entities\MontageType;

/**
 * Control system.
 *
 * @property int $id
 *
 * @property float $power
 * @property int $pumps_count
 * @property float $weight
 * @property float $price
 * @property string $description
 * @property int $gate_valves_count
 * @property int $type_id
 * @property string $article
 *
 * @property ControlSystemType $type
 * @property Currency $currency
 * @property Carbon $price_updated_at
 * @property YesNo $avr
 * @property YesNo $kkv
 * @property YesNo $on_street
 * @property YesNo $has_jockey
 * @property MontageType $montage_type
 */
final class ControlSystem extends Model
{
    use HasFactory, SoftDeletes, Cached;
    use HasPriceByRates;

    public $timestamps = false;
    protected $guarded = [];

    /**
     * @return string
     */
    protected static function getCacheKey(): string
    {
        return "control_systems";
    }

    protected $casts = [
        'currency' => Currency::class,
        'montage_type' => MontageType::class,
        'price_updated_at' => 'datetime:d.m.Y',
        'avr' => YesNo::class,
        'kkv' => YesNo::class,
        'on_street' => YesNo::class,
        'has_jockey' => YesNo::class,
    ];

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ControlSystemType::class, 'type_id');
    }
}
