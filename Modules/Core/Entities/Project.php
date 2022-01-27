<?php

namespace Modules\Core\Entities;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Permission;
use Modules\User\Traits\HasUserable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Project extends Model
{
    use HasFactory, SoftDeletes, UsesTenantConnection, UsesTenantModel, SoftCascadeTrait, Cloneable, HasUserable;

    protected $guarded = ['id'];
    public $timestamps = false;

    protected $softCascade = ['selections'];
    protected $cloneable_relations = ['selections'];

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

    /**
     * @return HasMany
     */
    public function selections(): HasMany
    {
        return $this->hasMany(Selection::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo($this->getUserClass());
    }
}
