<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class LimitCondition extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    use HasFactory, UsesTenantConnection;
}
