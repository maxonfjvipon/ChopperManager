<?php

namespace Modules\Pump\Entities;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpApplication extends Model
{
    use HasTranslations, UsesTenantConnection;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;

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
