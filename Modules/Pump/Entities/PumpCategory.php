<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpCategory extends Model
{
    public static int $SINGLE_PUMP = 1;
    public static int $DOUBLE_PUMP = 2;

    use HasTranslations, UsesTenantConnection;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
