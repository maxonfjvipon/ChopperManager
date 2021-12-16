<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class DN extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $fillable = ['value'];
    protected $table = 'dns';
    public $timestamps = false;
}
