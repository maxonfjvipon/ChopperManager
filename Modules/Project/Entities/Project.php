<?php

namespace Modules\Project\Entities;

use App\Traits\HasArea;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Traits\ProjectRelationShips;
use Modules\User\Entities\Area;
use Modules\User\Entities\ClientRole;
use Modules\User\Entities\User;
use phpDocumentor\Reflection\Types\Array_;
use Spatie\Permission\Models\Permission;

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
 * @property User $installer
 * @property User $designer
 * @property User $customer
 * @property User $dealer
 * @property Area $area
 *
 * @method static self find(int $id)
 */
class Project extends Model
{
    use HasFactory, HasArea, SoftDeletes, ProjectRelationShips;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
        'status' => ProjectStatus::class
    ];

    // BOOTED
    protected static function booted()
    {
        self::created(function (self $project) {
            if (!$project->user->isAdmin()) {
                $project->update(match ($project->user->client_role->value) {
                    ClientRole::Dealer => ['dealer_id' => $project->user->id],
                    ClientRole::DesignInstitute => ['designer_id' => $project->user->id],
                });
            }
        });
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return Auth::user()->isAdmin()
            ? $this->asArrayForAdmin()
            : $this->asArrayforClient();
    }

    /**
     * @return array
     */
    public function asArrayForAdmin(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'area' => $this->area->name,
            'status' => $this->status->description,
            'installer' => $this->installer?->full_name,
            'designer' => $this->designer?->full_name,
            'customer' => $this->customer?->full_name,
            'dealer' => $this->dealer?->full_name,
            'created_at' => formatted_date($this->created_at),
            'updated_at' => formatted_date($this->updated_at),
        ];
    }

    /**
     * @return array
     */
    public function asArrayforClient(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'area' => $this->area->name,
            'status' => $this->status->description,
            'created_at' => formatted_date($this->created_at),
            'updated_at' => formatted_date($this->updated_at),
        ];
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithAllPartners($query): mixed
    {
        return $query->with([
            'installer' => ($callback = function ($query) {
                $query->select('id', 'first_name', 'middle_name', 'last_name');
            }),
            'designer' => $callback,
            'customer' => $callback,
            'dealer' => $callback,
        ]);
    }
}
