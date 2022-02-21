<?php

namespace Modules\User\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

final class UserAndPumpSeries extends Model
{
    use HasFactory, HasCompositePrimaryKey;

    protected $guarded = [];
    public $timestamps = false;
    protected $table = "users_and_pump_series";
    protected $primaryKey = ['user_id', 'series_id'];
    public $incrementing = false;

    public static function updateAvailableSeriesForUser(array $available_series_ids, User $user): bool
    {
        self::where('user_id', $user->id)
            ->whereNotIn('series_id', $available_series_ids)
            ->delete();
        DB::table('users_and_pump_series')->insertOrIgnore(array_map(fn($seriesId) => [
            'user_id' => $user->id, 'series_id' => $seriesId
        ],  $available_series_ids));
        return Discount::updateForUser($available_series_ids, $user);
    }
}
