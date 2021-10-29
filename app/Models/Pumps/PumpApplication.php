<?php

namespace App\Models\Pumps;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpApplication extends Model
{
    use HasTranslations, UsesTenantConnection;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
}
