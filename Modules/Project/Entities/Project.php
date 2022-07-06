<?php

namespace Modules\Project\Entities;

use App\Traits\HasArea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Project\Traits\ProjectAttributes;
use Modules\Project\Traits\ProjectRelationships;
use Modules\Project\Traits\ProjectScopes;
use Modules\Selection\Entities\PumpStation;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Area;
use Modules\ProjectParticipant\Entities\Contractor;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\User\Entities\User;

/**
 * Project.
 *
 * @property int $id
 * @property string $name
 * @property int $area_id
 * @property int $installer_id
 * @property int $designer_id
 * @property int $customer_id
 * @property int $dealer_id
 * @property int $created_by
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ProjectStatus $status
 * @property User $user
 * @property Contractor $installer
 * @property Contractor $designer
 * @property Contractor $customer
 * @property Dealer $dealer
 * @property Area $area
 * @property Collection<Selection> $selections
 * @property array<string> $all_pump_station_names
 *
 * @method static self find(int $id)
 */
final class Project extends Model implements Arrayable
{
    use HasFactory, HasArea, SoftDeletes;
    use ProjectRelationships, ProjectScopes, ProjectAttributes;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
        'status' => ProjectStatus::class
    ];

    /**
     * @return array
     */
    public function asArray(): array
    {
        return array_merge(
            [
                'id' => $this->id,
                'name' => $this->name,
                'area' => $this->area->name,
                'status' => $this->status->description,
                'installer' => $this->installer?->full_name,
                'designer' => $this->designer?->full_name,
                'customer' => $this->customer?->full_name,
                'pump_stations' => $this->all_pump_station_names,
                'created_at' => formatted_date($this->created_at),
                'updated_at' => formatted_date($this->updated_at),
            ],
            Auth::user()->isAdmin() ? [
                'dealer' => $this->dealer?->name,
                'user' => $this->user->full_name,
            ] : []
        );
    }
}
