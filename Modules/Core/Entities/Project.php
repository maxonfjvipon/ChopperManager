<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Selection\Entities\SinglePumpSelection;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Project extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    use HasFactory, SoftDeletes, UsesTenantConnection;

    protected $casts = [
        'created_at' => 'datetime:d.m.Y'
    ];

    public function selections(): HasMany
    {
        return $this->hasMany(SinglePumpSelection::class);
    }
}
