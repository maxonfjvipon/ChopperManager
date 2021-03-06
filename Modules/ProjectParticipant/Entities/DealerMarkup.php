<?php

namespace Modules\ProjectParticipant\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Dealer markup.
 *
 * @property int $id
 * @property int $value
 *
 * @method static self create(array $attributes)
 */
final class DealerMarkup extends Model
{
    use HasFactory;

    protected $table = 'dealer_markups';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @param  array  $markups
     * @param  Dealer  $dealer
     * @return void
     */
    public static function updateForDealer(array $markups, Dealer $dealer): void
    {
        self::when(
            ! empty($markups),
            fn ($query) => $query->whereNotIn(
                'id',
                array_map(
                    fn ($markup) => self::updateOrCreate([
                        'dealer_id' => $dealer->id,
                        'cost_from' => $markup['cost_from'],
                        'cost_to' => $markup['cost_to'],
                    ], [
                        'value' => $markup['value'],
                    ])->id,
                    $markups
                )
            )
        )->delete();
    }
}
