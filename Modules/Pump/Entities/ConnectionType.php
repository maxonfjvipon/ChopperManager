<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class ConnectionType extends Model
{
    use HasTranslations, UsesTenantConnection;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
}
