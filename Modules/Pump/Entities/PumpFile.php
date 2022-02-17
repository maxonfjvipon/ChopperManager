<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Pump file.
 */
final class PumpFile extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $guarded = [];
    public $timestamps = false;
    protected $table = "pump_files";
}
