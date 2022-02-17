<?php

namespace Modules\Pump\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\Tenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Pump series application.
 */
final class PumpSeriesAndApplication extends Model
{
    use HasFactory, HasCompositePrimaryKey, UsesTenantConnection;

    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['series_id', 'application_id'];

    public static function createForSeries(PumpSeries $series, $applications)
    {
        if ($applications)
            return DB::table(Tenant::current()->database . '.pump_series_and_applications')
                ->insertOrIgnore(array_map(function ($application_id) use ($series) {
                    return ['application_id' => $application_id, 'series_id' => $series->id];
                }, $applications));
    }

    public static function updateForSeries(PumpSeries $series, $applications)
    {
        if ($applications) {
            self::whereSeriesId($series->id)
                ->whereNotIn('application_id', $applications)
                ->delete();
            return self::createForSeries($series, $applications);
        }
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(PumpApplication::class, 'application_id');
    }
}
