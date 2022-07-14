<?php

namespace Modules\ProjectParticipant\Entities;

use App\Traits\Cached;
use App\Traits\HasArea;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Project\Entities\Project;
use Modules\ProjectParticipant\Traits\DealerRelationships;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\User\Entities\Area;

/**
 * Dealer.
 *
 * @property int                                $id
 * @property string                             $name
 * @property string                             $email
 * @property string                             $itn
 * @property bool                               $without_pumps
 * @property Area                               $area
 * @property Carbon                             $created_at
 * @property string                             $phone
 * @property array<Project>|Collection<Project> $projects
 *
 * @method static self find(int|string $id)
 * @method static self create(array $attributes)
 */
final class Dealer extends Model implements Arrayable
{
    use HasFactory;
    use Cached;
    use DealerRelationships;
    use HasArea;
    public const BPE = 1;

    protected $table = 'dealers';

    protected $guarded = [];

    /**
     * @var string[]
     */
    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
        'without_pumps' => 'boolean',
    ];

    protected static function getCacheKey(): string
    {
        return 'dealers';
    }

    protected static function booted()
    {
        self::saved(fn () => self::clearCache());
        self::deleted(fn () => self::clearCache());
    }

    /**
     * Allow new created series to BPE dealer.
     */
    public static function allowNewSeriesToBPE(PumpSeries $series): int
    {
        return DealerPumpSeries::updateSeriesForDealer(
            array_merge(
                ($bpe = self::with(['available_series' => fn ($query) => $query->select('id')])
                    ->firstWhere('id', self::BPE))
                    ->available_series
                    ->map(fn ($series) => $series->id)
                    ->toArray(),
                [$series->id]
            ),
            $bpe
        );
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'created_at' => formatted_date($this->created_at),
            'name' => $this->name,
            'email' => $this->email,
            'itn' => $this->itn,
            'area' => $this->area->name,
            'phone' => $this->phone,
        ];
    }
}
