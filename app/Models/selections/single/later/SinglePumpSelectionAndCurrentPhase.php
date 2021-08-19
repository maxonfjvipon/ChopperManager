<?php

namespace App\Models\Selections\Single;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinglePumpSelectionAndCurrentPhase extends Model
{
    protected $table = 'single_pump_selections_and_current_phases';
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory;
}
