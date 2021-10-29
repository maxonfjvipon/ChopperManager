<?php

namespace App\Models\Pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpCategory extends Model
{
    use HasTranslations, UsesTenantConnection;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
