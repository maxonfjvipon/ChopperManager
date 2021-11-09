<?php

namespace Modules\Pump\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;
use Modules\Pump\Http\Requests\PumpSeriesUpdateRequest;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpSeriesAndApplication extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['series_id', 'application_id'];
    use HasFactory, HasCompositePrimaryKey, UsesTenantConnection;

    public static function createFromRequestForSeries(PumpSeriesStoreRequest $request, PumpSeries $series, $method = "insert")
    {
        if ($request->applications)
            return DB::table('pump_series_and_applications')
                ->insertOrIgnore(array_map(function ($application_id) use ($series) {
                    return ['application_id' => $application_id, 'series_id' => $series->id];
                }, $request->applications));
    }

    public static function updateFromRequestForSeries(PumpSeriesUpdateRequest $request, PumpSeries $series): bool
    {
        if ($request->applications) {
            self::whereSeriesId($series->id)
                ->whereNotIn('application_id', $request->applications)
                ->delete();
            return self::createFromRequestForSeries($request, $series, 'upsert');
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
