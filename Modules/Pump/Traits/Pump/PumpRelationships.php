<?php

namespace Modules\Pump\Traits\Pump;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCoefficients;
use Modules\Pump\Entities\PumpFile;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpsPriceList;
use Modules\Selection\Entities\Selection;
use Znck\Eloquent\Relations\BelongsToThrough;

trait PumpRelationships
{
    // RELATIONSHIPS
    public function series(): BelongsTo
    {
        return $this->belongsTo(PumpSeries::class, 'series_id');
    }

    public function brand(): BelongsToThrough
    {
        return $this->belongsToThrough(PumpBrand::class, PumpSeries::class, null, '', [
            PumpBrand::class => 'brand_id',
            PumpSeries::class => 'series_id'
        ]);
    }

    public function price_list(): HasOne
    {
        return $this->hasOne(PumpsPriceList::class, 'pump_id')
            ->where('country_id', Auth::user()->country_id);
    }

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

    public function mains_connection(): BelongsTo
    {
        return $this->belongsTo(MainsConnection::class, 'connection_id');
    }

    public function coefficients(): HasMany
    {
        return $this->hasMany(PumpCoefficients::class, 'pump_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(PumpFile::class, 'pump_id');
    }

    public function selections(): HasMany
    {
        return $this->hasMany(Selection::class, 'pump_id');
    }
}
