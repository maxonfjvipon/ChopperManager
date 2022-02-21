<?php

namespace Modules\User\Entities;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Pump\Entities\PumpSeries;
use Modules\User\Contracts\ChangeUser\WithAvailableProps;
use Modules\User\Traits\UserAttributes;
use Modules\User\Traits\UserRelationships;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

final class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;
    use HasRelationships, UserRelationships, UserAttributes;

    public $timestamps = false;
    protected $table = 'users';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'id', 'organization_name', 'itn', 'phone', 'city', 'first_name', 'middle_name',
        'last_name', 'email', 'password', 'business_id', 'country_id', 'currency_id', 'city', 'postcode',
        'is_active', 'last_login_at'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d.m.Y',
        'email_verified_at' => 'datetime:d.m.Y H:i',
        'last_login_at' => 'datetime: d.m.Y',
        'is_active' => 'boolean',
    ];

    /**
     * @return string
     */
    public function getForeignKey(): string
    {
        return 'user_id';
    }

    protected static function booted()
    {
        self::created(function (self $user) {
            UserAndPumpSeries::updateAvailableSeriesForUser(PumpSeries::pluck('id')->all(), $user);
            UserAndSelectionType::updateAvailableSelectionTypesForUser([1, 2], $user);
        });
    }

    public function updateAvailablePropsFromRequest(WithAvailableProps $request): bool
    {
        $data = $request->availableProps();
        return UserAndPumpSeries::updateAvailableSeriesForUser($data['available_series_ids'], $this)
            && UserAndSelectionType::updateAvailableSelectionTypesForUser($data['available_selection_type_ids'], $this);
    }

    public static function addNewSeriesForSuperAdmins(PumpSeries $series)
    {
        foreach (self::with(['available_series' => function ($query) {
            $query->select('id');
        }])->role('SuperAdmin')->get(['id'])->all() as $user) {
            UserAndPumpSeries::updateAvailableSeriesForUser(
                array_merge($user->available_series->map(fn($series) => $series->id)->toArray(), [$series->id]),
                $user
            );
        }
    }

    private function availableSeriesRelationQuery($seriesIds = []): Closure
    {
        return function ($query) use ($seriesIds) {
            $query->whereIn('id', empty($seriesIds)
                ? $this->available_series()->pluck('id')->all()
                : $seriesIds
            );
        };
    }

    /**
     * @return bool does user have super admin or admin role
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole('SuperAdmin', 'Admin');
    }
}
