<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentPhase extends Model
{
    protected $fillable = ['value', 'voltage'];
    public $timestamps = false;
    use HasFactory;
}
