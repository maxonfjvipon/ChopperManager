<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpsAndCoefficients extends Model
{
    use HasFactory, UsesTenantConnection;
    protected $table = "pumps_and_coefficients";
    protected $fillable = ['pump_id', 'position', 'k', 'b', 'c'];
    public $timestamps = false;
}
