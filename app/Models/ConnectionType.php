<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
