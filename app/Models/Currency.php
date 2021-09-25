<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['name', 'code'];
    public $timestamps = false;
    use HasFactory;

    public function getNameCodeAttribute()
    {
        return "{$this->code} / {$this->name}";
    }
}
