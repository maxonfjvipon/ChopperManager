<?php

namespace Modules\Pump\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

/**
 * Pump type.
 */
final class PumpType extends Model
{
    use HasTranslations, HasFactory, Cached;

    protected static function getCacheKey(): string
    {
        return "pump_types";
    }

    public $translatable = ['name'];
    protected $table = 'pump_types';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function series(): BelongsToMany
    {
        return $this->belongsToMany(PumpSeries::class, 'pump_series_and_types', 'type_id', 'series_id');
    }

    public function scopeAvailableForUserSeries($query, $seriesIds = [])
    {
        return $query->whereIn(
            'id',
            PumpSeriesAndType::whereIn(
                'series_id',
                empty($seriesIds)
                    ? Auth::user()->available_series()->pluck('id')->all()
                    : $seriesIds
            )->distinct()
                ->pluck('type_id')
                ->all()
        );
    }
}
