<?php

namespace App\Models\Pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class ElPowerAdjustment extends Model
{
    use HasTranslations;

    protected $table = "electronic_power_adjustments";
    public $translatable = ['name'];
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

}
