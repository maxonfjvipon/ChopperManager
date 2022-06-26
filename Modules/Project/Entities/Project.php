<?php

namespace Modules\Project\Entities;

use App\Traits\HasArea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Project\Traits\ProjectRelationShips;
use Modules\User\Entities\Area;
use Modules\User\Entities\Contractor;
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
 * @property User $dealer
 * @property Area $area
 *
 * @method static self find(int $id)
 */
final class Project extends Model implements Arrayable
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
                $project->update(['dealer_id' => $project->user->id]); // fixme
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
            'installer' => $this->installer?->name,
            'designer' => $this->designer?->name,
            'customer' => $this->customer?->name,
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
            'installer' => ($callback = fn($query) => $query->select('id', 'name')),
            'designer' => $callback,
            'customer' => $callback,
            'dealer' => fn($query) => $query->select('id', 'first_name', 'middle_name', 'last_name'),
        ]);
    }
}
