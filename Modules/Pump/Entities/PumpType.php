<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpType extends Model
{
    use HasTranslations, HasFactory, UsesTenantConnection;

    public $translatable = ['name'];
    protected $table = 'pump_types';
    protected $fillable = ['name'];
    public $timestamps = false;
}
