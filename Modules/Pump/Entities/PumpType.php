<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpType extends Model
{
    use HasTranslations, HasFactory, UsesTenantConnection;

    public $translatable = ['name'];
    protected $table = 'pump_types';
    protected $fillable = ['name'];
    public $timestamps = false;

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
