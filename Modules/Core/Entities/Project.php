<?php

namespace Modules\Core\Entities;

use App\Traits\WithOrWithoutTrashed;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Bkwld\Cloner\Cloneable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Core\Support\Rates;
use Modules\PumpManager\Entities\PMUser;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Traits\ConstructsSelectionCurves;
use Modules\User\Entities\Permission;
use Modules\User\Traits\HasUserable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Project extends Model
{
    use HasFactory, SoftDeletes, UsesTenantConnection, UsesTenantModel, SoftCascadeTrait, Cloneable, HasUserable, WithOrWithoutTrashed;

    protected $guarded = ['id'];
    public $timestamps = false;

    protected $softCascade = ['selections'];
    protected array $cloneable_relations = ['selections'];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y'
    ];

    // BOOTED
    protected static function booted()
    {
        self::created(function (self $project) {
            $permission = Permission::create([
                'guard_name' => Tenant::current()->guard,
                'name' => 'project_access_' . $project->id
            ]);
            Auth::user()->givePermissionTo($permission->name);
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

    // FUNCTIONS

    /**
     * @param Request $request
     * @return $this
     * @throws Exception
     */
    public function readyForExport(Request $request): self
    {
        $rates = Rates::new();
        $this->load(['selections' => function ($query) use ($request) {
            $query->whereIn('id', $request->selection_ids);
        },
            'selections.pump',
            'selections.pump.series',
            'selections.pump.series.category',
            'selections.pump.series.power_adjustment',
            'selections.pump.series.discount',
            'selections.pump.brand',
            'selections.pump.connection_type',
            'selections.pump.price_list',
            'selections.pump.price_list.currency',
        ])->selections->transform(fn(Selection $selection) => $selection->withPrices($rates)->withCurves());;
        return $this;
    }

    // RELATIONSHIPS

    public function all_selections()
    {
        return $this->hasMany(Selection::class)->withTrashed();
    }

    /**
     * @return HasMany
     */
    public function selections(): HasMany
    {
        return $this->hasMany(Selection::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo($this->getUserClass());
    }

    /**
     * @return HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(ProjectStatus::class, 'id', 'status_id');
    }

    /**
     * @return HasOne
     */
    public function delivery_status(): HasOne
    {
        return $this->hasOne(ProjectDeliveryStatus::class, 'id', 'delivery_status_id');
    }
}
