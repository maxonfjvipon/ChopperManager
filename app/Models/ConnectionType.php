<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Connection type.
 */
class ConnectionType extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];
}
