<?php

namespace Modules\Pump\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Electric power adjustment
 */
final class ElPowerAdjustment extends Model
{
    use HasFactory, HasTranslations, UsesTenantConnection, Cached;

    protected static function getCacheKey(): string
    {
        return "power_adjustments";
    }

    protected $table = "electronic_power_adjustments";
    public $translatable = ['name'];
    protected $guarded = [];
    public $timestamps = false;

}
