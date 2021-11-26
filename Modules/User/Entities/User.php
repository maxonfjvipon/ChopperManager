<?php

namespace Modules\User\Entities;

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

// TODO: default user with no specific fields and methods
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, UsesTenantConnection;

    protected $fillable = [
        'id', 'organization_name', 'itn', 'phone', 'city', 'first_name', 'middle_name',
        'last_name', 'email', 'password', 'business_id', 'country_id', 'currency_id', 'city', 'postcode'
    ];

    public $timestamps = false;

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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:d.m.Y H:i',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }


    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, 'user_id');
    }

    public static function allExceptSuperAdmins($columns = ['*'])
    {
        return parent::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'SuperAdmin');
        })->get($columns)->all();
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->middle_name}";
    }
}
