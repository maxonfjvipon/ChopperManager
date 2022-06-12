<?php

namespace Modules\Selection\Entities;

use App\Traits\WithOrWithoutTrashed;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Selection.
 *
 * @property int $id
 * @property float $head
 * @property float $flow
 * @property int $project_id
 * @property float $deviation
 * @property string $main_pumps_counts
 * @property int $reserve_pumps_count
 * @property string $control_system_type_ids
 * @property string $pump_brand_ids
 * @property string $pump_series_ids
 * @property string $collectors
 * @property string $comment
 * @property int $pump_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property StationType $station_type
 * @property SelectionType $type
 * @property Collection<PumpStation>|array<PumpStation> $pump_stations
 *
 * @method static self create(array $attributes)
 */
final class Selection extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait, WithOrWithoutTrashed;

    protected $guarded = [];
    protected array $softCascade = ['pump_stations'];

    protected $casts = [
        'station_type' => StationType::class,
        'type' => SelectionType::class,
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];

    /**
     * @return HasMany
     */
    public function pump_stations(): HasMany
    {
        return $this->hasMany(PumpStation::class, 'selection_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pump_stations_to_show(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->pump_stations()
            ->with([
                'control_system' => fn($query) => $query->select('id', 'type_id'),
                'control_system.type' => fn($query) => $query->select('id', 'name', 'station_type'),
                'pump' => fn($query) => $query->select('id', 'name', 'series_id'),
                'pump.series' => fn($query) => $query->select('id', 'name'),
                'input_collector' => fn($query) => $query->select('id', 'dn_common', 'material')
            ])
            ->get();
    }
}
