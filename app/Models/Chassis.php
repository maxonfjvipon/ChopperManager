<?php

namespace App\Models;

use App\Traits\HasCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Chassis.
 */
class Chassis extends Model
{
    use HasFactory, HasCurrency, SoftDeletes;

    public $timestamps = false;
    protected $table = "chassis";
    protected $guarded = [];
}
