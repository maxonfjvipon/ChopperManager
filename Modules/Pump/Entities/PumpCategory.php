<?php


namespace Modules\Pump\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Pump category.
 */
final class PumpCategory extends Model
{
    use HasTranslations, UsesTenantConnection, Cached;

    public static int $SINGLE_PUMP = 1;
    public static int $DOUBLE_PUMP = 2;

    protected static function getCacheKey(): string
    {
        return "pump_categories";
    }

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
