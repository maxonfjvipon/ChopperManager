<?php

namespace Modules\User\Entities;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\Currency;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Country extends Model
{
    public $translatable = ['name'];
    protected $fillable = ['name', 'code', 'currency_id'];
    public $timestamps = false;
    use HasFactory, UsesTenantConnection, HasTranslations;

    public function getCountryCodeAttribute(): string
    {
        return "{$this->name} / {$this->code}";
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
