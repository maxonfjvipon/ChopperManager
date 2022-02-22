<?php

namespace Modules\Project\Entities;

use App\Support\Rates\RealRates;
use App\Support\Rates\StickyRates;
use App\Traits\WithOrWithoutTrashed;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Bkwld\Cloner\Cloneable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Traits\ProjectRelationShips;
use Modules\Selection\Entities\Selection;
use Spatie\Permission\Models\Permission;

/**
 * Project.
 * @property mixed $selections
 * @property int $status_id
 * @property string $name
 * @property int $id
 */
final class Project extends Model
{
    use HasFactory, SoftDeletes , SoftCascadeTrait, Cloneable;
    use WithOrWithoutTrashed, ProjectRelationShips;

    protected $guarded = ['id'];
    public $timestamps = false;

    protected array $softCascade = ['selections'];
    protected array $cloneable_relations = ['selections'];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y'
    ];

    // BOOTED
    protected static function booted()
    {
        self::created(function (self $project) {
            Auth::user()->givePermissionTo(
                Permission::create([
                    'name' => 'project_access_' . $project->id
                ])->name
            );
        });
        self::deleted(function (self $project) {
            if ($project->status_id !== 4 && $project->status_id !== 3)
                $project->update(['status_id' => 4]);
        });
        self::restored(function (self $project) {
            if ($project->status_id === 4 || $project->status_id === 3)
                $project->update(['status_id' => 1]);
        });
    }

    /**
     * Prepare project for export
     * @param Request $request
     * @return $this
     * @throws Exception
     */
    public function readyForExport(Request $request): self
    {
        $rates = StickyRates::new(RealRates::new());
        $this->load(['selections' => function ($query) use ($request) {
            $query->whereIn('id', $request->selection_ids);
        },
            'selections.pump',
            'selections.pump.series',
            'selections.pump.series.category',
            'selections.pump.series.power_adjustment',
            'selections.pump.series.auth_discount',
            'selections.pump.brand',
            'selections.pump.connection_type',
            'selections.pump.price_list',
            'selections.pump.price_list.currency',
        ])->selections->transform(fn(Selection $selection) => $selection->withPrices($rates)->withCurves());
        return $this;
    }
}
