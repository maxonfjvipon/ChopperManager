<?php

namespace Modules\Pump\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class DN extends Model
{
    use HasFactory, UsesTenantConnection, Cached;

    protected static function getCacheKey(): string
    {
        return "dns";
    }

    protected $fillable = ['value'];
    protected $table = 'dns';
    public $timestamps = false;
}
