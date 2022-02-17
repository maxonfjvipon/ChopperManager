<?php

namespace Modules\Pump\Entities;

use App\Traits\Cached;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Double pump work scheme
 */
final class DoublePumpWorkScheme extends Model
{
    use HasFactory, HasTranslations, UsesTenantConnection, Cached;

    protected static function getCacheKey(): string
    {
        return "dp_work_schemes";
    }

    protected $table = 'double_pump_work_schemes';
    protected $fillable = ['id', 'name'];
    public $timestamps = false;
    public $translatable = ['name'];
}
