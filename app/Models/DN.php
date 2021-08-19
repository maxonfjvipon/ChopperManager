<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DN extends Model
{
    protected $fillable = ['value'];
    protected $table = 'dns';
    public $timestamps = false;
    use HasFactory;
}
