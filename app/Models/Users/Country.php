<?php

namespace App\Models\Users;

use App\Models\Currency;
use App\Traits\HasTranslations;
use App\Traits\HasUsersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Country extends Model
{
    public $translatable = ['name'];
    protected $fillable = ['name', 'code', 'currency_id'];
    public $timestamps = false;
    use HasFactory, HasUsersTrait, UsesTenantConnection, HasTranslations;

    public function getCountryCodeAttribute(): string
    {
        return "{$this->name} / {$this->code}";
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
