<?php

namespace Modules\Pump\Actions;

use App\Support\ArrForFilteringWithId;
use Illuminate\Database\Eloquent\Builder;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Http\Requests\RqLoadPumps;
use Modules\Pump\Transformers\RcPumpToShow;
use Modules\PumpSeries\Entities\PumpBrand;

/**
 * Load pumps action.
 */
final class AcLoadPumps extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(RqLoadPumps $request)
    {
        parent::__construct(
            new ArrMerged(
                [
                    'pumps' => [
                        'total' => ($pumps = self::getPumps($request))->count(),
                        'items' => RcPumpToShow::collection($pumps
                            ->offset((array_key_exists('current', $request->pagination ?? [])
                                    ? $request->pagination['current'] - 1
                                    : 0)
                                * ($request->pagination['pageSize'] ?? 10))
                            ->limit($request->pagination['pageSize'] ?? 10)
                            ->get()),
                    ],
                ],
                new ArrIf(
                    (bool) $request->brand,
                    fn () => new ArrObject(
                        'filter_data',
                        new ArrForFilteringWithId([
                            'series' => array_merge(...PumpBrand::with([
                                'series' => fn ($query) => $query->select('id', 'name', 'brand_id'),
                            ])
                                ->whereIn('id', $request->brand)
                                ->get(['id', 'name'])
                                ->map(fn (PumpBrand $brand) => $brand->series->all())),
                        ])
                    )
                )
            )
        );
    }

    /**
     * Load pumps from database.
     */
    private static function getPumps(RqLoadPumps $request): mixed
    {
        return Pump::with([
            'series' => fn ($query) => $query->select('id', 'name', 'brand_id', 'currency', 'is_discontinued'),
            'series.brand' => fn ($query) => $query->select('id', 'name'),
        ])->when(
            $request->sortField && $request->sortOrder,
            fn (Builder $query) => $query->orderBy($request->sortField, $request->sortOrder)
        )->when(
            $request->brand, fn (Builder $query) => $query->whereHas(
            'series.brand',
            fn ($query) => $query->whereIn('pump_brands.id', $request->brand))
        )->when(
            $request->series,
            fn (Builder $query) => $query->whereHas(
                'series',
                fn ($query) => $query->whereIn('pump_series.id', $request->series)
            )
        )->when(
            $request->collector_switch,
            fn (Builder $query) => $query->whereIn('collector_switch', $request->collector_switch)
        )->when(
            $request->connection_type,
            fn (Builder $query) => $query->whereIn('connection_type', $request->connection_type)
        )->when(
            $request->dn_suction,
            fn (Builder $query) => $query->whereIn('dn_suction', $request->dn_suction)
        )->when(
            $request->dn_pressure,
            fn (Builder $query) => $query->whereIn('dn_pressure', $request->dn_pressure)
        )->when(
            $request->orientation,
            fn (Builder $query) => $query->whereIn('orientation', $request->orientation)
        )->when(
            $request->search,
            fn (Builder $query) => $query->where('article', 'like', "%$request->search%")
                ->orWhere('name', 'like', "%$request->search%")
                ->orWhereRelation('series.brand', fn ($query) => $query->where('pump_brands.name', 'like', "%$request->search%"))
                ->orWhereRelation('series', fn ($query) => $query->where('pump_series.name', 'like', "%$request->search%"))
        );
    }
}
