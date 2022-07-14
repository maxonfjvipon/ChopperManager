<?php

namespace Modules\ProjectParticipant\Entities;

use App\Traits\Cached;
use App\Traits\HasArea;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Project\Entities\Project;
use Modules\ProjectParticipant\Traits\ContractorRelationships;
use Modules\User\Entities\Area;

/**
 * Contractor.
 *
 * @property int    $id
 * @property string $name
 * @property string $itn
 * @property string $full_nam
 * @property Area   $area
 * @property Carbon $created_at
 *
 * @method static self firstOrCreate(array $find, array $attributes)
 * @method static self find(int|string $id)
 */
final class Contractor extends Model implements Arrayable
{
    use HasFactory;
    use Cached;
    use HasArea;
    use ContractorRelationships;
    public const SEPARATOR = '?';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];

    protected static function booted()
    {
        self::saved(fn () => self::clearCache());
    }

    protected static function getCacheKey(): string
    {
        return 'contractors';
    }

    public function getFullNameAttribute(): string
    {
        return implode(' / ', [$this->name, $this->itn, $this->area->name]);
    }

    public function withAllProjects(): self
    {
        $this->{'projects'} = Project::with([
            'area' => fn ($q) => $q->select('id', 'name'),
            'installer',
            'designer',
            'customer',
            'user' => fn ($query) => $query->select('id', 'first_name', 'middle_name', 'last_name'),
        ])
            ->where('installer_id', $this->id)
            ->orWhere('customer_id', $this->id)
            ->orWhere('designer_id', $this->id)
            ->get();

        return $this;
    }

    /**
     * @return Contractor|null
     */
    public static function getOrCreateFrom(?string $contractorToCreate, string $separator = '?'): ?self
    {
        return $contractorToCreate
            ? self::firstOrCreate([
                'itn' => ($elems = explode($separator, $contractorToCreate))[1],
                'area_id' => $areaId = Area::getByRegionKladrId($elems[2])->id,
            ], [
                'name' => $elems[0],
                'itn' => $elems[1],
                'area_id' => $areaId,
            ])
            : null;
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'created_at' => formatted_date($this->created_at),
            'name' => $this->name,
            'itn' => $this->itn,
            'area' => $this->area->name,
        ];
    }
}
