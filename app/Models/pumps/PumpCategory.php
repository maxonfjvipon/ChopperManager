<?php

namespace App\Models\pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumpCategory extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
