<?php

namespace Modules\User\Entities;

use App\Traits\Cached;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Project\Entities\Currency;

/**
 * Country.
 * @property int $id
 * @property string $country_code
 * @property string $name
 * @property string $code
 */
final class Country extends Model
{
    use HasFactory, HasTranslations, Cached;

    public array $translatable = ['name'];
    protected $fillable = ['name', 'code', 'currency_id'];
    public $timestamps = false;

    protected static function getCacheKey(): string
    {
        return "countries";
    }

    public function getCountryCodeAttribute(): string
    {
        return "{$this->name} / {$this->code}";
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
