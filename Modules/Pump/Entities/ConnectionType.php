<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use App\Traits\Cached;

/**
 * Connection type
 * @property string $name
 * @package Modules\Pump\Entities
 */
final class ConnectionType extends Model
{
    use HasTranslations, Cached;

    protected static function getCacheKey(): string
    {
        return "connection_types";
    }

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
}
