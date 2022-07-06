<?php

namespace Modules\ProjectParticipant\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

/**
 * Dealers pump series link
 */
final class DealerPumpSeries extends Model
{
    use HasFactory, HasCompositePrimaryKey;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = "dealers_pump_series";
    protected $primaryKey = ['dealer_id', 'series_id'];
    protected $guarded = [];

    /**
     * @param array<int> $series_ids
     * @param Dealer $dealer
     * @return int
     */
    public static function updateSeriesForDealer(array $series_ids, Dealer $dealer): int
    {
        self::where('dealer_id', $dealer->id)
            ->whereNotIn('series_id', $series_ids)
            ->delete();
        return DB::table('dealers_pump_series')
            ->insertOrIgnore(array_map(fn($seriesId) => [
                'dealer_id' => $dealer->id, 'series_id' => $seriesId
            ], $series_ids));
    }
}
