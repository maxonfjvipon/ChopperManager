<?php


namespace Modules\Pump\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

/**
 * Pump category.
 * @property string $name
 */
final class PumpCategory extends Model
{
    use HasFactory, HasTranslations, Cached;

    public static int $SINGLE_PUMP = 1;
    public static int $DOUBLE_PUMP = 2;

    protected static function getCacheKey(): string
    {
        return "pump_categories";
    }

    public array $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
}
