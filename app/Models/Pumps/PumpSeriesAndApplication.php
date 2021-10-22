<?php

namespace App\Models\Pumps;

use App\Http\Requests\PumpSeriesStoreRequest;
use App\Http\Requests\PumpSeriesUpdateRequest;
use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class PumpSeriesAndApplication extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['series_id', 'application_id'];
    use HasFactory, HasCompositePrimaryKey;

    public static function createFromRequestForSeries(PumpSeriesStoreRequest $request, PumpSeries $series, $method = "insert")
    {
        return DB::table('pump_series_and_applications')
            ->insertOrIgnore(array_map(function ($application_id) use ($series) {
                return ['application_id' => $application_id, 'series_id' => $series->id];
            }, $request->applications));
    }

    public static function updateFromRequestForSeries(PumpSeriesUpdateRequest $request, PumpSeries $series): bool
    {
        self::whereSeriesId($series->id)
            ->whereNotIn('application_id', $request->applications)
            ->delete();
        return self::createFromRequestForSeries($request, $series, 'upsert');
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
