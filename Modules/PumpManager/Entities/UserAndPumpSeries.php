<?php

namespace Modules\PumpManager\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\Tenant;
use Modules\User\Entities\Discount;
use Modules\User\Entities\Userable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class UserAndPumpSeries extends Model
{
    use HasFactory, HasCompositePrimaryKey, UsesTenantConnection;

    protected $guarded = [];
    public $timestamps = false;
    protected $table = "users_and_pump_series";
    protected $primaryKey = ['user_id', 'series_id'];
    public $incrementing = false;

    public static function updateAvailableSeriesForUser(array $available_series_ids, Userable $user): bool
    {
        self::where('user_id', $user->id)
            ->whereNotIn('series_id', $available_series_ids)
            ->delete();
        DB::table(Tenant::current()->database . '.users_and_pump_series')->insertOrIgnore(array_map(fn($seriesId) => [
            'user_id' => $user->id, 'series_id' => $seriesId
        ],  $available_series_ids));
        return Discount::updateForUser($available_series_ids, $user);
    }
}
