<?php

namespace App\Models;

use App\Traits\HasCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Assembly job.
 */
class AssemblyJob extends Model
{
    use HasFactory, HasCurrency, SoftDeletes;

    public $timestamps = false;
    protected $guarded = [];

    public function control_system_type(): BelongsTo
    {
        return $this->belongsTo(ControlSystemType::class, 'control_system_type_id');
    }

    public function control_system(): BelongsTo
    {
        return $this->belongsTo(ControlSystem::class, 'control_system_id');
    }
}
