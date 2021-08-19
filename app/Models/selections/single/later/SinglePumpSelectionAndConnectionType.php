<?php

namespace App\Models\selections\single;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinglePumpSelectionAndConnectionType extends Model
{
    protected $table = 'single_pump_selections_and_connection_types';
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory;
}
