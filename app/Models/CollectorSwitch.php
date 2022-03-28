<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Collector switch.
 */
class CollectorSwitch extends Model
{
    use HasFactory;

    protected $table = 'collector_switches';
    public $timestamps = false;
    protected $guarded = [];
}
