<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class ConnectionType extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
