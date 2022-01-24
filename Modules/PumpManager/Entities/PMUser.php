<?php

namespace Modules\PumpManager\Entities;

use Closure;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\PumpSeries;
use Modules\User\Contracts\ChangeUser\WithAvailableProps;
use Modules\User\Entities\Business;
use Modules\User\Entities\Userable;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class PMUser extends Userable
{
    use HasRelationships;

    protected $fillable = [
        'id', 'organization_name', 'itn', 'phone', 'city', 'first_name', 'middle_name',
        'last_name', 'email', 'password', 'business_id', 'country_id', 'currency_id', 'city', 'postcode',
        'is_active',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:d.m.Y H:i',
        'is_active' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    protected static function booted()
    {
        static::created(function (self $user) {
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
     * @return HasManyDeep
     */
    public function available_pumps(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->available_series(), (new PumpSeries())->pumps());
    }

    /**
     * Available brands for user
     *
     * @return HasManyDeep
     */
    public function available_brands(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->available_series(), (new PumpSeries())->brand());
    }

    /**
     * Available series for user
     *
     * @return BelongsToMany
     */
    public function available_series(): BelongsToMany
    {
        $curTenantDB = Tenant::current()->database;
        return $this->belongsToMany(PumpSeries::class, $curTenantDB . '.users_and_pump_series', 'user_id', 'series_id');
    }

    /**
     * Available selection types for user
     *
     * @return BelongsToMany
     */
    public function available_selection_types(): BelongsToMany
    {
        $curTenantDB = Tenant::current()->database;
        return $this->belongsToMany(SelectionType::class, $curTenantDB . '.users_and_selection_types', 'user_id', 'type_id');
    }
}
