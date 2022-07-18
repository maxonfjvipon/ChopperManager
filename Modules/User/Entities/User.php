<?php

namespace Modules\User\Entities;

use App\Traits\HasArea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Project\Entities\Project;
use Modules\ProjectParticipant\Entities\Contractor;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\User\Traits\UserRelationships;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * User.
 *
 * @property int                                      $id
 * @property string                                   $full_name
 * @property string                                   $first_name
 * @property string                                   $middle_name
 * @property string                                   $last_name
 * @property string                                   $itn
 * @property bool                                     $is_active
 * @property string                                   $phone
 * @property int                                      $dealer_id
 * @property string                                   $email
 * @property UserRole                                 $role
 * @property Carbon                                   $created_at
 * @property Area                                     $area
 * @property Dealer                                   $dealer
 * @property array<Contractor>|Collection<Contractor> $contractors
 * @property array<Project>|Collection<Project>       $projects
 *
 * @method static self create(array $attributes)
 * @method static self find(int|string $id)
 */
final class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasArea;
    use SoftDeletes;
    use UserRelationships;
    use HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'middle_name',
        'last_name',
        'itn',
        'area_id',
        'dealer_id',
        'phone',
        'is_active',
        'role',
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
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
        'is_active' => 'boolean',
        'role' => UserRole::class,
    ];

    /**
     * @return bool does user have super admin or admin role
     */
    public function isAdmin(): bool
    {
        return $this->role->is(UserRole::Admin);
    }

    public function getFullNameAttribute(): string
    {
        return implode(' ', [
            $this->first_name,
            $this->middle_name,
            $this->last_name ?? '',
        ]);
    }
}
