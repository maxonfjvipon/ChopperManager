<?php

namespace App\Models\Pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class PumpType extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    protected $table = 'pump_types';
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
