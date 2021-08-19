<?php

namespace App\Models\selections\single;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinglePumpSelectionAndPumpRegulation extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'single_pump_selections_and_pump_regulations';
    use HasFactory;
}
