<?php

namespace Modules\PumpSeries\Entities;

use App\Models\Enums\Currency;
use App\Traits\CanBeDiscontinued;
use App\Traits\PluckCached;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pump\Entities\Pump;

/**
 * Pump series.
 *
 * @property int       $id
 * @property string    $name
 * @property bool      $is_discontinued
 * @property int       $brand_id
 * @property PumpBrand $brand
 * @property Currency  $currency
 *
 * @method static PumpSeries create(array $attributes)
 * @method static PumpSeries find(string|int $id)
 */
final class PumpSeries extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SoftCascadeTrait;
    use CanBeDiscontinued;
    use PluckCached;

    protected $table = 'pump_series';

    public $timestamps = false;

    protected $guarded = [];

    protected array $softCascade = ['pumps'];

    protected $casts = [
        'currency' => Currency::class,
        'is_discontinued' => 'boolean',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(PumpBrand::class, 'brand_id', 'id');
    }

    public function pumps(): HasMany
    {
        return $this->hasMany(Pump::class, 'series_id');
    }
}
