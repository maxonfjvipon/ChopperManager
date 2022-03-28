<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * DN.
 */
class DN extends Model
{
    use HasFactory;

    protected $table = "dns";
    protected $guarded = [];
    public $timestamps = false;
}
