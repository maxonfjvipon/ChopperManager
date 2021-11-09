<?php

namespace Modules\Pump\Entities;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Znck\Eloquent\Traits\BelongsToThrough;

class Pump extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];
    public $timestamps = false;
    protected $softCascade = ['selections'];

    use SoftDeletes, HasFactory, BelongsToThrough, SoftCascadeTrait, UsesTenantConnection;

    public function getFullNameAttribute(): string
    {
        return "{$this->brand->name} {$this->series->name} {$this->name}";
    }

    public function getTypesAttribute(): string
    {
        return $this->series->imploded_types;
    }

    public function getApplicationsAttribute(): string
    {
        return $this->series->imploded_applications;
    }

    public function getPriceListAttribute()
    {
        $price_lists = $this->price_lists;
        if (count($price_lists) === 0) {
            return null;
        }
        return $price_lists->where('country_id', Auth::user()->country_id)->first();
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }

    public function brand(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(PumpBrand::class, PumpSeries::class, null, '', [
            PumpBrand::class => 'brand_id',
            PumpSeries::class => 'series_id'
        ]);
    }

//    public function selections(): HasMany
//    {
//        return $this->hasMany(SinglePumpSelection::class);
//    }

    public function price_lists(): HasMany
    {
        return $this->hasMany(PumpsPriceList::class);
    }

//    public function applications(): BelongsToMany
//    {
//        return $this->belongsToMany(PumpApplication::class, 'pumps_and_applications', 'pump_article_num', 'application_id');
//    }
//
//    public function types(): BelongsToMany
//    {
//        return $this->belongsToMany(PumpType::class, 'pumps_and_types', 'pump_article_num', 'type_id');
//    }

//    public function currency(): BelongsTo
//    {
//        return $this->belongsTo(Currency::class);
//    }

    public function connection_type(): BelongsTo
    {
        return $this->belongsTo(ConnectionType::class);
    }

    public function dn_suction(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_suction_id');
    }

    public function dn_pressure(): BelongsTo
    {
        return $this->belongsTo(DN::class, 'dn_pressure_id');
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(MainsConnection::class, 'connection_id');
    }

    public function coefficients(): HasMany
    {
        return $this->hasMany(PumpsAndCoefficients::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(PumpFile::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/jpg', 'image/png']);
        $this->addMediaCollection('file')
            ->acceptsMimeTypes(['application/pdf']);
    }
}
