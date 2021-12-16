<?php

namespace Modules\Pump\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\Tenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class PumpSeriesAndType extends Model
{
    protected $table = 'pump_series_and_types';
    protected $primaryKey = ['series_id', 'type_id'];
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory, HasCompositePrimaryKey, UsesTenantConnection, UsesTenantModel;

    public static function createForSeries(PumpSeries $series, $types)
    {
        if ($types) {
            return DB::table(Tenant::current()->database . '.pump_series_and_types')
                ->insertOrIgnore(array_map(function ($type_id) use ($series) {
                    return ['type_id' => $type_id, 'series_id' => $series->id];
                }, $types));
        }
    }

    public static function updateForSeries(PumpSeries $series, $types)
    {
        if ($types) {
            self::whereSeriesId($series->id)
                ->whereNotIn('type_id', $types)
                ->delete();
            return self::createForSeries($series, $types);
        }
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
