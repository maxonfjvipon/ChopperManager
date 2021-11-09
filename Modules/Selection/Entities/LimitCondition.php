<?php

namespace Modules\Selection\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class LimitCondition extends Model
{
    use HasFactory, UsesTenantConnection;

    public $timestamps = false;
    protected $guarded = [];
}
