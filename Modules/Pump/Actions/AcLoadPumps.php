<?php

namespace Modules\Pump\Actions;

use App\Support\ArrForFilteringWithId;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Requests\RqLoadPumps;
use Modules\Pump\Transformers\RcPumpToShow;
use Modules\PumpSeries\Entities\PumpBrand;

final class AcLoadPumps
{
    /**
     * @param RqLoadPumps $request
     * @return Arrayable
     * @throws Exception
     */
    public function __invoke(RqLoadPumps $request): Arrayable
    {
        $pumps = Pump::with(['series' => function ($query) {
            $query->select('id', 'name', 'brand_id', 'currency');
        }, 'series.brand' => function ($query) {
            $query->select('id', 'name');
        }])
            ->when($request->sortField && $request->sortOrder, function (Builder $query) use ($request) {
                $query->orderBy($request->sortField, $request->sortOrder);
            })
            ->when($request->brand, function (Builder $query) use ($request) {
                $query->whereHas('series.brand', fn($query) => $query->whereIn('pump_brands.id', $request->brand));
            })
            ->when($request->series, function (Builder $query) use ($request) {
                $query->whereHas('series', fn($query) => $query->whereIn('pump_series.id', $request->series));
            })
            ->when($request->collector_switch, function (Builder $query) use ($request) {
                $query->whereIn('collector_switch', $request->collector_switch);
            })
            ->when($request->connection_type, function (Builder $query) use ($request) {
                $query->whereIn('connection_type', $request->connection_type);
            })
            ->when($request->dn_suction, function (Builder $query) use ($request) {
                $query->whereIn('dn_suction', $request->dn_suction);
            })
            ->when($request->dn_pressure, function (Builder $query) use ($request) {
                $query->whereIn('dn_pressure', $request->dn_pressure);
            })
            ->when($request->orientation, function (Builder $query) use ($request) {
                $query->whereIn('orientation', $request->orientation);
            })
            ->when($request->search, function (Builder $query) use ($request) {
                $query->where('article', 'like', "%$request->search%")
                    ->orWhere('name', 'like', "%$request->search%")
                    ->orWhereRelation('series.brand', fn($query) => $query->where('pump_brands.name', 'like', "%$request->search%"))
                    ->orWhereRelation('series', fn($query) => $query->where('pump_series.name', 'like', "%$request->search%"));
            });
        return new ArrMerged(
            ['pumps' => [
                'total' => $pumps->count(),
                'items' => RcPumpToShow::collection($pumps
                    ->offset((array_key_exists('current', $request->pagination ?? [])
                            ? $request->pagination['current'] - 1
                            : 0)
                        * ($request->pagination['pageSize'] ?? 10))
                    ->limit($request->pagination['pageSize'] ?? 10)
                    ->get()),
            ]], new ArrIf(
                !!$request->brand,
                new ArrObject(
                    'filter_data',
                    new ArrFromCallback(
                        fn() => new ArrForFilteringWithId([
                            'series' => array_merge(...PumpBrand::with(['series' => function ($query) {
                                $query->select('id', 'name', 'brand_id');
                            }])
                                ->whereIn('id', $request->brand)
                                ->get(['id', 'name'])
                                ->map(fn(PumpBrand $brand) => $brand->series->all())),
                        ])
                    )
                )
            )
        );
    }
}
