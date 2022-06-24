<?php

namespace Modules\User\Entities;

use App\Traits\HasArea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Pure;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\User\Traits\UserRelationships;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * User.
 *
 * @property int $id
 * @property string $full_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $itn
 * @property boolean $is_active
 *
 * @property UserRole $role
 *
 * @method static self create(array $attributes)
 */
final class User extends Authenticatable
{
    use HasFactory, Notifiable, HasArea, SoftDeletes;
    use UserRelationships, HasRelationships;

    public $timestamps = false;

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
        'created_at' => 'datetime:d.m.Y',
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

    /**
     * @param PumpSeries $series
     * @return void
     */
    public static function allowNewSeriesToAdmins(PumpSeries $series)
    {
        foreach (self::with(['available_series' => fn($query) => $query->select('id')])
                     ->where('role', UserRole::Admin)
                     ->get(['id'])
                     ->all() as $user) {
            UserPumpSeries::updateSeriesForUser(
                array_merge(
                    $user->available_series
                        ->map(fn($series) => $series->id)
                        ->toArray(),
                    [$series->id]
                ),
                $user
            );
        }
    }

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
}
