<?php

namespace Modules\Selection\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Entities\Project;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\Pump;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class SinglePumpSelection extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    use HasFactory, SoftDeletes, UsesTenantConnection;

    protected $casts = [
        'created_at' => 'datetime:d.m.Y',
        'updated_at' => 'datetime:d.m.Y',
        'power_limit_checked' => 'boolean',
        'ptp_length_limit_checked' => 'boolean',
        'dn_suction_limit_checked' => 'boolean',
        'dn_pressure_limit_checked' => 'boolean',
        'use_additional_filters' => 'boolean',
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
}
