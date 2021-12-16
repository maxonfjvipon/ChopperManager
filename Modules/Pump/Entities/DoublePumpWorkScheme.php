<?php

namespace Modules\Pump\Entities;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class DoublePumpWorkScheme extends Model
{
    use HasFactory, HasTranslations, UsesTenantConnection;

    protected $table = 'double_pump_work_schemes';
    protected $fillable = ['id', 'name'];
    public $timestamps = false;
    public $translatable = ['name'];
}
