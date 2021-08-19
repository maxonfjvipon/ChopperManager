<?php

namespace App\Models\selections;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumpSelectionType extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
