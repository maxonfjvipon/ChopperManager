<?php

namespace Modules\PumpManager\Entities;

use Closure;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Core\Entities\Currency;
use Modules\Core\Entities\Project;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;
use Modules\PumpManager\Http\Requests\UpdateUserRequest;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Entities\Discount;

class  User extends \Modules\User\Entities\User
{
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

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->middle_name}";
    }

    public function getAvailablePumpsAttribute()
    {
        return $this->available_pumps()->get()->all();
    }

    public function getAvailableBrandsAttribute()
    {
        return $this->available_brands()->get()->all();
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

    public function updateAvailablePropsFromRequest(UpdateUserRequest $request): bool
    {
        return UserAndPumpSeries::updateAvailableSeriesForUser($request->available_series_ids, $this)
            && UserAndSelectionType::updateAvailableSelectionTypesForUser($request->available_selection_type_ids, $this);
    }

    private function availableSeriesRelationQuery(): Closure
    {
        return function ($query) {
            $query->whereIn('id', $this->available_series()->pluck('id')->all());
        };
    }

    public function available_pumps()
    {
        return Pump::whereHas('series', $this->availableSeriesRelationQuery());
    }

    public function available_brands()
    {
        return PumpBrand::whereHas('series', $this->availableSeriesRelationQuery());
    }

    public function available_series(): BelongsToMany
    {
        $curTenantDB = Tenant::current()->database;
        return $this->belongsToMany(PumpSeries::class, $curTenantDB . '.users_and_pump_series', 'user_id', 'series_id');
    }


    public function available_selection_types(): BelongsToMany
    {
        $curTenantDB = Tenant::current()->database;
        return $this->belongsToMany(SelectionType::class, $curTenantDB . '.users_and_selection_types', 'user_id', 'type_id');
    }
}
