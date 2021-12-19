<?php

namespace Modules\Selection\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Entities\Project;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\Pump\Entities\Pump;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Selection extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $table = "selections";
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

    public function getPumpTypeAttribute()
    {
        return $this->pump->pumpable_type;
    }

    public function pump(): BelongsTo
    {
        return $this->belongsTo(Pump::class, 'pump_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function dp_work_scheme(): BelongsTo
    {
        return $this->belongsTo(DoublePumpWorkScheme::class, 'dp_work_scheme_id');
    }
}
