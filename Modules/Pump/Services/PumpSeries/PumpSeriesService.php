<?php


namespace Modules\Pump\Services\PumpSeries;

use App\Traits\HasFilterData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpType;
use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;

class PumpSeriesService implements PumpSeriesServiceInterface
{
    use HasFilterData;

    protected function indexFilterData(): array
    {
        return $this->asFilterData([
            'brands' => PumpBrand::pluck('name')->all(),
            'categories' => PumpCategory::pluck('name')->all(),
            'power_adjustments' => ElPowerAdjustment::pluck('name')->all(),
            'applications' => PumpApplication::pluck('name')->all(),
            'types' => PumpType::pluck('name')->all(),
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

    public function __index(): Response
    {
        return Inertia::render($this->indexPath(), [
            'filter_data' => $this->indexFilterData(),
            'brands' => PumpBrand::all(),
            'series' => PumpSeries::with(['brand', 'category', 'power_adjustment'])
                ->get()
                ->map(fn($series) => [
                    'id' => $series->id,
                    'brand' => $series->brand->name,
                    'name' => $series->name,
                    'category' => $series->category->name,
                    'power_adjustment' => $series->power_adjustment->name,
                    'applications' => $series->imploded_applications,
                    'types' => $series->imploded_types
                ])
                ->all(),
        ]);
    }

    public function __store(PumpSeriesStoreRequest $request): RedirectResponse
    {
        PumpSeries::createFromRequest($request);
        return Redirect::route('pump_series.index');
    }
}
