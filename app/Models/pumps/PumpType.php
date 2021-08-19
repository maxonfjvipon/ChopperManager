<?php

namespace App\Models\Pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumpType extends Model
{
    protected $table = 'pump_types';
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
