<?php

namespace App\Models\Pumps;

use App\Models\ConnectionType;
use App\Models\Currency;
use App\Models\CurrentPhase;
use App\Models\DN;
use App\Models\Selections\Single\SinglePumpSelection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Inertia\Testing\Concerns\Has;
use Znck\Eloquent\Traits\BelongsToThrough;

class Pump extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    use HasFactory;
    use BelongsToThrough;

    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }

    public function producer(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(PumpProducer::class, PumpSeries::class, null, '', [
            PumpProducer::class => 'producer_id',
            PumpSeries::class => 'series_id'
        ]);
    }

    public function selections(): HasMany
    {
        return $this->hasMany(SinglePumpSelection::class);
    }

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(PumpApplication::class, 'pumps_and_applications', 'pump_id', 'application_id');
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(PumpType::class, 'pumps_and_types', 'pump_id', 'type_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function connection_type(): BelongsTo
    {
        return $this->belongsTo(ConnectionType::class);
    }

    public function dn_input(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_input_id');
    }

    public function dn_output(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_output_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PumpCategory::class, 'category_id');
    }

    public function regulation(): BelongsTo
    {
        return $this->belongsTo(PumpRegulation::class, 'regulation_id');
    }

    public function phase(): BelongsTo
    {
        return $this->belongsTo(CurrentPhase::class, 'phase_id');
    }
}
