<?php

namespace App\Models\pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumpAndApplication extends Model
{
    protected $table = 'pumps_and_applications';
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory;
}
