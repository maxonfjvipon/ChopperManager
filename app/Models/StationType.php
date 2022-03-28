<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Station type.
 */
class StationType extends Model
{
    use HasFactory;

    protected $table = "station_types";
    public $timestamps = false;
    protected $guarded = [];
}
