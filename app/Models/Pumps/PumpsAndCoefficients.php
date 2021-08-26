<?php

namespace App\Models\Pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumpsAndCoefficients extends Model
{
    use HasFactory;
    protected $table = "pumps_and_coefficients";
    protected $fillable = ['pump_id', 'count', 'k', 'b', 'c'];
    public $timestamps = false;
}
