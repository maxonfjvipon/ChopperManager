<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Business extends Model
{
    use HasFactory, HasTranslations, UsesTenantConnection;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
}
