<?php

namespace Modules\User\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

final class UserPumpSeries extends Model
{
    use HasFactory, HasCompositePrimaryKey;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = "users_pump_series";
    protected $primaryKey = ['user_id', 'series_id'];
    protected $guarded = [];

    /**
     * @param array $series_ids
     * @param User $user
     * @return int
     */
    public static function updateSeriesForUser(array $series_ids, User $user): int
    {
        self::where('user_id', $user->id)
            ->whereNotIn('series_id', $series_ids)
            ->delete();
        return DB::table('users_pump_series')->insertOrIgnore(array_map(fn($seriesId) => [
            'user_id' => $user->id, 'series_id' => $seriesId
        ], $series_ids));
    }
}
