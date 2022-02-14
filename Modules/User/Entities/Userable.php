<?php

namespace Modules\User\Entities;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFiltered;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrValues;
use Maxonfjvipon\Elegant_Elephant\Text\TxtImploded;
use Modules\Core\Entities\Currency;
use Modules\Core\Entities\Project;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;
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
        'last_login_at' => 'datetime: d.m.Y',
    ];

    // ATTRIBUTES

    /**
     * @throws Exception
     */
    public function getFullNameAttribute(): string
    {
        return TxtImploded::new(
            " ",
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        )->asString();
    }

    /**
     * @return string
     */
    public function getLastNameAttribute(): string
    {
        return $this->last_name ?? "";
    }

    /**
     * @throws Exception
     */
    public function getFormattedDiscountsAttribute(): array
    {
        return ArrValues::new(
            ArrMapped::new(
                ArrFiltered::new(
                    [...$this->discounts()
                        ->where('discountable_type', 'pump_brand')
                        ->with(['discountable' => function (MorphTo $morphTo) {
                            $morphTo->morphWith([
                                PumpBrand::class => [
                                    'series',
                                    'series.discount' => function ($query) {
                                        $query->where('user_id', $this->id);
                                    }
                                ]
                            ]);
                        }])
                        ->get()],
                    fn($discount) => $discount->discountable
                ),
                fn($discount) => [
                    'key' => $discount->discountable_id . '-' . $discount->discountable_type . '-' . $discount->user_id,
                    'discountable_id' => $discount->discountable_id,
                    'discountable_type' => $discount->discountable_type,
                    'user_id' => $discount->user_id,
                    'name' => $discount->discountable->name,
                    'value' => $discount->value,
                    'children' => ArrValues::new(
                        ArrMapped::new(
                            ArrFiltered::new(
                                [...$discount->discountable->series],
                                fn($series) => $series->discount
                            ),
                            fn($series) => [
                                'key' => $series->discount->discountable_id
                                    . '-' . $series->discount->discountable_type
                                    . '-' . $series->discount->user_id,
                                'discountable_id' => $series->discount->discountable_id,
                                'discountable_type' => $series->discount->discountable_type,
                                'user_id' => $series->discount->user_id,
                                'name' => $series->name,
                                'value' => $series->discount->value,
                            ]
                        )
                    )->asArray(),
                ]
            )
        )->asArray();
    }

    /**
     * @return bool does user have super admin or admin role
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole('SuperAdmin', 'Admin');
    }

    public function available_series()
    {
        return PumpSeries::all();
    }

    // RELATIONS
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
