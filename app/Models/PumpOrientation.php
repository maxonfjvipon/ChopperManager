<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumpOrientation extends Model
{
    use HasFactory;

    protected $table = "pump_orientations";
    public $timestamps = false;
    protected $guarded = [];
}
