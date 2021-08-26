<?php

namespace App\Models\Selections\Single;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinglePumpSelectionAndPumpType extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'single_pump_selections_and_pump_types';
    use HasFactory;
}
