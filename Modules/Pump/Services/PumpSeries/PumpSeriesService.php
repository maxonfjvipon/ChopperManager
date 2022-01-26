<?php

namespace Modules\Pump\Services\PumpSeries;

use App\Traits\HasFilterData;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Contracts\PumpSeries\PumpSeriesContract;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpType;

abstract class PumpSeriesService implements PumpSeriesContract
{
    use HasFilterData;

    public function index(): Response
    {
        $brands = PumpBrand::all();
        return Inertia::render($this->indexPath(), [
            'filter_data' => $this->asFilterData([
                'brands' => $brands->pluck('name')->all(),
                'categories' => PumpCategory::pluck('name')->all(),
                'power_adjustments' => ElPowerAdjustment::pluck('name')->all(),
                'applications' => PumpApplication::pluck('name')->all(),
                'types' => PumpType::pluck('name')->all(),
            ]),
            'brands' => $brands,
            'series' => PumpSeries::with(['brand', 'category', 'power_adjustment', 'types', 'applications'])
                ->get()
                ->map(fn($series) => [
                    'id' => $series->id,
                    'brand' => $series->brand->name,
                    'name' => $series->name,
                    'category' => $series->category->name,
                    'power_adjustment' => $series->power_adjustment->name,
                    'applications' => $series->imploded_applications,
                    'types' => $series->imploded_types,
                    'is_discontinued' => $series->is_discontinued,
                ])->all()
        ]);
    }

    public function indexPath(): string
    {
        return 'Pump::PumpSeries/Index';
    }

    public function showPath(): string
    {
        return 'Pump::PumpSeries/Show';
    }

    public function editPath(): string
    {
        return 'Pump::PumpSeries/Edit';
    }

    public function createPath(): string
    {
        return 'Pump::PumpSeries/Create';
    }
}
