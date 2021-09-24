<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainsConnection extends Model
{
    protected $fillable = ['phase', 'voltage'];
    public $timestamps = false;
    use HasFactory;

    public function getFullValueAttribute()
    {
        return "{$this->value} ({$this->voltage})";
    }
}
