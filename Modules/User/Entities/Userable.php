<?php

namespace Modules\User\Entities;

use Dotenv\Loader\Resolver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Core\Entities\Currency;
use Modules\Core\Entities\Project;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Permission\Traits\HasRoles;

abstract class Userable extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, UsesTenantConnection;

    public $timestamps = false;
    protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return string
     */
    public function getForeignKey(): string
    {
        return 'user_id';
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:d.m.Y H:i',
    ];

    /**
     * @return bool does user have super admin or admin role
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole('SuperAdmin', 'Admin');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->middle_name}";
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, 'user_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
