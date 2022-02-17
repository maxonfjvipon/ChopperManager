<?php

namespace Modules\Core\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Currency.
 * @property string $code
 * @property string $name
 * @property int $id
 */
final class Currency extends Model
{
    use HasFactory, UsesTenantConnection, Cached;

    protected $fillable = ['name', 'code'];
    public $timestamps = false;

    protected static function getCacheKey(): string
    {
        return "currencies";
    }

    public function getCodeNameAttribute(): string
    {
        return "{$this->code} / {$this->name}";
    }
}
