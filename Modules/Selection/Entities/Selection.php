<?php

namespace Modules\Selection\Entities;

use App\Traits\WithOrWithoutTrashed;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Modules\Components\Entities\YesNo;
use Modules\Project\Entities\Project;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Traits\SelectionRelationships;

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
 * @property int $jockey_pump_id
 * @property float $jockey_flow
 * @property float $jockey_head
 * @property int $gate_valves_count
 * @property string $jockey_brand_ids
 * @property string $jockey_series_ids
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property StationType $station_type
 * @property SelectionType $type
 * @property Collection<PumpStation>|array<PumpStation> $pump_stations
 * @property Project $project
 * @property Pump $jockey_pump
 * @property YesNo $avr
 * @property YesNo $kkv
 * @property YesNo $on_street
 *
 * @method static self create(array $attributes)
 */
final class Selection extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait, WithOrWithoutTrashed;
    use SelectionRelationships;

    protected $guarded = [];
    protected array $softCascade = ['pump_stations'];

    protected $casts = [
        'station_type' => StationType::class,
        'type' => SelectionType::class,
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
        'avr' => YesNo::class,
        'kkv' => YesNo::class,
        'on_street' => YesNo::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pump_stations_to_show(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->pump_stations()
            ->with([
                'control_system' => fn($query) => $query->select('id', 'type_id', 'avr', 'kkv', 'on_street', 'gate_valves_count', 'has_jockey'),
                'control_system.type' => fn($query) => $query->select('id', 'name', 'station_type'),
                'pump' => fn($query) => $query->select('id', 'name', 'series_id'),
                'pump.series' => fn($query) => $query->select('id', 'name'),
                'input_collector' => fn($query) => $query->select('id', 'dn_common', 'material'),
                'selection' => fn($query) => $query->select('id', 'jockey_pump_id', 'jockey_flow', 'jockey_head', 'flow', 'head'),
            ])
            ->get();
    }
}
