<?php

namespace App\Models\Users;

use App\Traits\HasUsersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Business extends Model
{
    use HasFactory, HasUsersTrait, HasTranslations, UsesTenantConnection;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
}
