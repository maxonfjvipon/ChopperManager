<?php

namespace Modules\Pump\Services\Pumps;

use App\Traits\HasFilterData;
use Closure;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpType;
use Modules\Pump\Transformers\PumpResource;

class PumpsService implements PumpsServiceInterface
{
    use HasFilterData;

    /**
     * @return array
     */
    protected function pumpFilterData(): array
    {
        return $this->asFilterData([
            'brands' => PumpBrand::pluck('name')->all(),
            'series' => PumpSeries::pluck('name')->all(),
            'categories' => PumpCategory::pluck('name')->all(),
            'connections' => ConnectionType::pluck('name')->all(),
            'dns' => DN::pluck('value')->all(),
            'power_adjustments' => ElPowerAdjustment::pluck('name')->all(),
            'mains_connections' => MainsConnection::all()->map(fn($mc) => $mc->full_value)->toArray(),
            'types' => PumpType::pluck('name')->all(),
            'applications' => PumpApplication::pluck('name')->all(),
        ]);
    }

    protected function lazyLoadedPumps($pumps = null): Closure
    {
        return fn() => ($pumps ?: new Pump)->with([
            'series',
            'series.brand',
            'series.power_adjustment',
            'series.category',
            'series.applications',
            'series.types'
        ])
            ->with('connection')
            ->with('dn_suction')
            ->with('dn_pressure')
            ->with('connection_type')
            ->with(['price_lists' => function ($query) {
                $query->where('country_id', Auth::user()->country_id);
            }, 'price_lists.currency'])
            ->get()
            ->map(fn($pump) => [
                'id' => $pump->id,
                'article_num_main' => $pump->article_num_main,
                'article_num_reserve' => $pump->article_num_reserve,
                'article_num_archive' => $pump->article_num_archive,
                'brand' => $pump->series->brand->name,
                'series' => $pump->series->name,
                'name' => $pump->name,
                'weight' => $pump->weight,
                'price' => $pump->price_lists[0]->price ?? null,
                'currency' => $pump->price_lists[0]->currency->code ?? null,
                'rated_power' => $pump->rated_power,
                'rated_current' => $pump->rated_current,
                'connection_type' => $pump->connection_type->name,
                'fluid_temp_min' => $pump->fluid_temp_min,
                'fluid_temp_max' => $pump->fluid_temp_max,
                'ptp_length' => $pump->ptp_length,
                'dn_suction' => $pump->dn_suction->value,
                'dn_pressure' => $pump->dn_pressure->value,
                'category' => $pump->series->category->name,
                'power_adjustment' => $pump->series->power_adjustment->name,
                'mains_connection' => $pump->connection->full_value,
                'applications' => $pump->applications,
                'types' => $pump->types,
            ])->all();
    }

    public function __index(): Response
    {
        return Inertia::render($this->indexPath(), [
            'pumps' => Inertia::lazy($this->lazyLoadedPumps()),
            'filter_data' => $this->pumpFilterData()
        ]);
    }

    public function __show(Pump $pump): Response
    {
        return Inertia::render($this->showPath(), [
            'pump' => new PumpResource($pump),
        ]);
    }

    public function indexPath(): string
    {
        return 'Pump::Pumps/Index';
    }

    public function showPath(): string
    {
        return 'Pump::Pumps/Show';
    }

    public function editPath(): string
    {
    }

    public function createPath(): string
    {
    }
}
