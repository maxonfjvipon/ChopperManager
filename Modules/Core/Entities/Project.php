<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Permission;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Project extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    use HasFactory, SoftDeletes, UsesTenantConnection, UsesTenantModel;

    protected $casts = [
        'created_at' => 'datetime:d.m.Y'
    ];

    protected static function booted()
    {
        self::created(function (self $project) {
            $permission = Permission::create([
                'guard_name' => Tenant::current()->guard,
                'name' => 'project_access_' . $project->id
            ]);
            Auth::user()->givePermissionTo($permission->name);
        });
    }

    public function selections(): HasMany
    {
        return $this->hasMany(Selection::class);
    }
}
