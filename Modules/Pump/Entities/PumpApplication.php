<?php

namespace Modules\Pump\Entities;

use App\Traits\Cached;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

/**
 * Pump application.
 * @method static availableForUserSeries($availableSeriesIds)
 */
final class PumpApplication extends Model
{
    use HasTranslations, Cached;

    protected static function getCacheKey(): string
    {
        return "pump_applications";
    }

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;

    public function series(): BelongsToMany
    {
        return $this->belongsToMany(PumpSeries::class, 'pump_series_and_applications', 'application_id', 'series_id');
    }

    public function scopeAvailableForUserSeries($query, $seriesIds = [])
    {
        return $query->whereIn(
            'id',
            PumpSeriesAndApplication::whereIn(
                'series_id',
                empty($seriesIds)
                    ? Auth::user()->available_series()->pluck('id')->all()
                    : $seriesIds
            )->distinct()
                ->pluck('application_id')
                ->all()
        );
    }
}
