<?php

namespace Modules\Selection\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Limit condition.
 */
final class LimitCondition extends Model
{
    use HasFactory, UsesTenantConnection, Cached;

    public $timestamps = false;
    protected $guarded = [];

    protected static function getCacheKey(): string
    {
        return "limit_conditions";
    }
}
