<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Montage type.
 */
class MontageType extends Model
{
    use HasFactory;

    protected $table = "montage_types";
    public $timestamps = false;
    protected $guarded = [];
}
