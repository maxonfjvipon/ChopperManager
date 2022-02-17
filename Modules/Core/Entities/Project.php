<?php

namespace Modules\Core\Entities;

use App\Support\Rates\Rates;
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
use Modules\AdminPanel\Entities\Tenant;
use Modules\Core\Traits\ProjectRelationShips;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Permission;
use Modules\User\Traits\HasUserable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

/**
 * Project.
 * @property mixed $selections
 * @property int $status_id
 * @property string $name
 * @property int $id
 */
final class Project extends Model
{
    use HasFactory, SoftDeletes, UsesTenantConnection, UsesTenantModel, SoftCascadeTrait, Cloneable, HasUserable;
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
                    'guard_name' => Tenant::current()->guard,
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
        return $this->load(['selections' => function ($query) use ($request) {
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
    }
}
