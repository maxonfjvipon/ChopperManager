<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Pump file.
 */
final class PumpFile extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;
    protected $table = "pump_files";
}
