<?php

namespace App\Models\Selections\Single;

use App\Models\ConnectionType;
use App\Models\CurrentPhase;
use App\Models\DN;
use App\Models\LimitCondition;
use App\Models\Project;
use App\Models\Pumps\Pump;
use App\Models\Pumps\PumpProducer;
use App\Models\Pumps\PumpRegulation;
use App\Models\Pumps\PumpType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SinglePumpSelection extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    use HasFactory;

//    public static function boot() {
//        parent::boot();
//
//        static::saving(function($model){
//            foreach ($model->toArray() as $name => $value) {
//                if ($value === null) {
//                    $model->{$name} = null;
//                }
//            }
//        });
//    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function pump(): BelongsTo
    {
        return $this->belongsTo(Pump::class, 'pump_id');
    }

    public function powerLimitCondition(): BelongsTo
    {
        return $this->belongsTo(LimitCondition::class, 'power_limit_condition_id');
    }

    public function dnInputLimitCondition(): BelongsTo
    {
        return $this->belongsTo(LimitCondition::class, 'dn_input_limit_condition_id');
    }

    public function dnOutputLimitCondition(): BelongsTo
    {
        return $this->belongsTo(LimitCondition::class, 'dn_output_limit_condition_id');
    }

    public function betweenAxesLimitCondition(): BelongsTo
    {
        return $this->belongsTo(LimitCondition::class, 'between_axes_limit_condition_id');
    }

    public function dnInputLimit(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_input_limit_id');
    }

    public function dnOutputLimit(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_output_limit_id');
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
//            CurrentPhase::class, 'single_pump_selections_and_current_phases',
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
//            PumpRegulation::class, 'single_pump_selections_and_pump_regulations',
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
