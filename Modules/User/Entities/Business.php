<?php

namespace Modules\User\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Business extends Model
{
    use HasFactory, HasTranslations, UsesTenantConnection, Cached;

    protected static function getCacheKey(): string
    {
        return "businesses";
    }

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
}
