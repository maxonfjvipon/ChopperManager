<?php

namespace App\Models\Users;

use App\Models\Currency;
use App\Models\Discount;
use App\Models\Project;
use App\Models\Pumps\PumpBrand;
use App\Models\Pumps\PumpSeries;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

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
        'email_verified_at' => 'datetime',
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

    protected static function booted()
    {
        static::created(function (User $user) {
            $user->discounts()->insert(PumpSeries::all()->map(function ($series) use ($user) {
                return ['user_id' => $user->id, 'discountable_id' => $series->id, 'discountable_type' => 'pump_series'];
            })->toArray());
            $user->discounts()->insert(PumpBrand::all()->map(function ($brand) use ($user) {
                return ['user_id' => $user->id, 'discountable_id' => $brand->id, 'discountable_type' => 'pump_brand'];
            })->toArray());
        });
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->middle_name}";
    }
}
