<?php

namespace App\Models\Pumps;

use App\Http\Requests\PumpSeriesStoreRequest;
use App\Http\Requests\PumpSeriesUpdateRequest;
use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class PumpSeriesAndType extends Model
{
    protected $table = 'pump_series_and_types';
    protected $primaryKey = ['series_id', 'type_id'];
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory, HasCompositePrimaryKey;

    public static function createFromRequestForSeries(PumpSeriesStoreRequest $request, PumpSeries $series, $method = "insert")
    {
        return DB::table('pump_series_and_types')
            ->insertOrIgnore(array_map(function ($type_id) use ($series) {
                return ['type_id' => $type_id, 'series_id' => $series->id];
            }, $request->types));
    }

    public static function updateFromRequestForSeries(PumpSeriesUpdateRequest $request, PumpSeries $series): bool
    {
        self::whereSeriesId($series->id)
            ->whereNotIn('type_id', $request->types)
            ->delete();
        return self::createFromRequestForSeries($request, $series, 'upsert');
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PumpType::class, 'type_id');
    }
}
