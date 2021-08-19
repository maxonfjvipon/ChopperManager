<?php

namespace App\Models\pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumpAndType extends Model
{
    protected $table = 'pumps_and_types';
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory;
}
