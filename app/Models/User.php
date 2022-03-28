<?php

namespace App\Models;

use App\Traits\HasArea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * User.
 *
 * @property int $id
 * @property string $full_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $itn
 *
 * @property Organization $organization
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasArea, HasRoles, SoftDeletes;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'login',
        'password',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'itn',
        'area_id',
        'description',
        'address',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d.m.Y',
    ];

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return implode(" ", [
            $this->first_name,
            $this->middle_name,
            $this->last_name ?? ""
        ]);
    }

    /**
     * @return string
     */
    public function getItnAttribute(): string
    {
        return $this->getOriginal('itn') ?? $this->organization->itn;
    }

    /**
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'project_id');
    }
}
