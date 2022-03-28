<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Selection type.
 */
class SelectionType extends Model
{
    use HasFactory;

    protected $table = "selection_types";
    public $timestamps = false;
    protected $guarded = [];
}
