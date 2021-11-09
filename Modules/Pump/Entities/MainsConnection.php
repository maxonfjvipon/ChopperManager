<?php

namespace Modules\Pump\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class MainsConnection extends Model
{
    protected $fillable = ['phase', 'voltage'];
    public $timestamps = false;
    use HasFactory, UsesTenantConnection;

    protected $appends = ['full_value'];

    public function getFullValueAttribute()
    {
        return "{$this->phase}({$this->voltage})";
    }

}
