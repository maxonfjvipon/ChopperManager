<?php

namespace Modules\PumpSeries\Entities;

use App\Models\Enums\Country;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Pump brand.
 *
 * @property int $id
 * @property string $name
 *
 * @property Country $country
 */
class PumpBrand extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    protected $table = "pump_brands";
    public $timestamps = false;
    protected $guarded = [];
    protected array $softCascade = ['series'];

    protected $casts = [
        'country' => Country::class
    ];

    public static function allForFiltering(): Collection
    {
        return self::all(['id', 'name']);
    }

    public function series(): HasMany
    {
        return $this->hasMany(PumpSeries::class, 'brand_id');
    }
}
