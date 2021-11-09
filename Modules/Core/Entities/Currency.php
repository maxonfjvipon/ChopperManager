<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Currency extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $fillable = ['name', 'code'];
    public $timestamps = false;

    public function getNameCodeAttribute()
    {
        return "{$this->code} / {$this->name}";
    }
}
