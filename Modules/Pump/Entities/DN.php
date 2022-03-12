<?php

namespace Modules\Pump\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * DN
 * @property int $value
 */
final class DN extends Model
{
    use HasFactory, Cached;

    protected static function getCacheKey(): string
    {
        return "dns";
    }

    protected $fillable = ['value'];
    protected $table = 'dns';
    public $timestamps = false;
}
