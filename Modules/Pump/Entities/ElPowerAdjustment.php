<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class ElPowerAdjustment extends Model
{

    protected $table = "electronic_power_adjustments";
    public $translatable = ['name'];
    use HasFactory, HasTranslations, UsesTenantConnection;

    protected $guarded = [];
    public $timestamps = false;

}
