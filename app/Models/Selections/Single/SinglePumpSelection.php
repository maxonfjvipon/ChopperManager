<?php

namespace App\Models\Selections\Single;

use App\Models\ConnectionType;
use App\Models\MainsConnection;
use App\Models\DN;
use App\Models\LimitCondition;
use App\Models\Project;
use App\Models\Pumps\Pump;
use App\Models\Pumps\PumpBrand;
use App\Models\Pumps\ElPowerAdjustment;
use App\Models\Pumps\PumpType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SinglePumpSelection extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    use HasFactory, SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime:d.m.Y',
        'updated_at' => 'datetime:d.m.Y',
        'power_limit_checked' => 'boolean',
        'ptp_length_limit_checked' => 'boolean',
        'dn_suction_limit_checked' => 'boolean',
        'dn_pressure_limit_checked' => 'boolean'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function pump(): BelongsTo
    {
        return $this->belongsTo(Pump::class);
    }

    public function powerLimitCondition(): BelongsTo
    {
        return $this->belongsTo(LimitCondition::class, 'power_limit_condition_id');
    }

    public function dnSuctionLimitCondition(): BelongsTo
    {
        return $this->belongsTo(LimitCondition::class, 'dn_suction_limit_condition_id');
    }

    public function dnPressureLimitCondition(): BelongsTo
    {
        return $this->belongsTo(LimitCondition::class, 'dn_pressure_limit_condition_id');
    }

    public function ptpLengthLimitCondition(): BelongsTo
    {
        return $this->belongsTo(LimitCondition::class, 'ptp_length_limit_condition_id');
    }

    public function dnSuctionLimit(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_suction_limit_id');
    }

    public function dnPressureLimit(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_pressure_limit_id');
    }

//    TODO: MB sometimes later
//    public function connectionTypes(): BelongsToMany
//    {
//        return $this->belongsToMany(
//            ConnectionType::class, 'single_pump_selections_and_connection_types',
//            'selection_id', 'connection_type_id'
//        );
//    }
//
//    public function phases(): BelongsToMany
//    {
//        return $this->belongsToMany(
//            MainsPhase::class, 'single_pump_selections_and_current_phases',
//            'selection_id', 'phase_id'
//        );
//    }
//
//    public function mainPumpsCounts(): HasMany
//    {
//        return $this->hasMany(SinglePumpSelectionAndMainPumpsCount::class, 'selection_id');
//    }
//
//    public function pumpProducers(): BelongsToMany
//    {
//        return $this->belongsToMany(
//            PumpProducer::class, 'single_pump_selections_and_pump_producers',
//            'selection_id', 'producer_id'
//        );
//    }
//
//    public function pumpRegulations(): BelongsToMany
//    {
//        return $this->belongsToMany(
//            ElPowerAdjustment::class, 'single_pump_selections_and_pump_regulations',
//            'selection_id', 'regulation_id'
//        );
//    }
//
//    public function pumpTypes(): BelongsToMany
//    {
//        return $this->belongsToMany(
//            PumpType::class, 'single_pump_selections_and_pump_types',
//            'selection_id', 'type_id'
//        );
//    }

}
