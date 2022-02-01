<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use App\Traits\Cached;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Connection type
 * @package Modules\Pump\Entities
 */
class ConnectionType extends Model
{
    use HasTranslations, UsesTenantConnection, Cached;

    protected static function getCacheKey(): string
    {
        return "connection_types";
    }

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
}
