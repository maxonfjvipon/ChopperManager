<?php

namespace Modules\Selection\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\LimitCondition;

/**
 * Pumps for select from
 * @package Modules\Selection\Support
 */
final class ArrPumpsForSelecting implements Arrayable
{
    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * Ctor.
     * @param Request $req
     */
    public function __construct(Request $req)
    {
        $this->request = $req;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return [...Pump::whereIn('series_id', $this->request->series_ids)
            ->whereIsDiscontinued(false)
            ->with([
                'series',
                'series.auth_discount',
                'price_list',
                'price_list.currency',
                'brand',
                'dn_suction',
                'dn_pressure',
                'coefficients' => function ($query) {
                    $query->whereBetween(
                        'position',
                        [1, match ($this->request->pumpable_type) {
                            Pump::$DOUBLE_PUMP => 2,
                            Pump::$SINGLE_PUMP => max($this->request->main_pumps_counts) + $this->request->reserve_pumps_count
                        }]
                    );
                }
            ])
            ->onPumpableType($this->request->pumpable_type)
            ->when($this->request->use_additional_filters, function ($query) {
                $query
                    ->when(
                        $this->request->mains_connection_ids && count($this->request->mains_connection_ids) > 0,
                        function ($query) {
                            $query->whereIn('connection_id', $this->request->mains_connection_ids);
                        })
                    ->when(
                        $this->request->connection_type_ids && count($this->request->connection_type_ids) > 0,
                        function ($query) {
                            $query->whereIn('connection_type_id', $this->request->connection_type_ids);
                        })
                    ->when(
                        $this->request->dn_suction_limit_checked &&
                        $this->request->dn_suction_limit_condition_id &&
                        $this->request->dn_suction_limit_id,
                        function ($query) {
                            $query->whereRelation('dn_suction',
                                'id',
                                LimitCondition::allOrCached()->find($this->request->dn_suction_limit_condition_id)->value,
                                $this->request->dn_suction_limit_id
                            );
                        })
                    ->when(
                        $this->request->dn_pressure_limit_checked &&
                        $this->request->dn_pressure_limit_condition_id &&
                        $this->request->dn_pressure_limit_id,
                        function ($query) {
                            $query->whereRelation('dn_pressure',
                                'id',
                                LimitCondition::allOrCached()->find($this->request->dn_pressure_limit_condition_id)->value,
                                $this->request->dn_pressure_limit_id
                            );
                        })
                    ->when(
                        $this->request->power_limit_checked &&
                        $this->request->power_limit_condition_id &&
                        $this->request->power_limit_value,
                        function ($query) {
                            $query->where(
                                'rated_power',
                                LimitCondition::allOrCached()->find($this->request->power_limit_condition_id)->value,
                                $this->request->power_limit_value
                            );
                        })
                    ->when(
                        $this->request->ptp_length_limit_checked &&
                        $this->request->ptp_length_limit_condition_id &&
                        $this->request->ptp_length_limit_value,
                        function ($query) {
                            $query->where(
                                'ptp_length',
                                LimitCondition::allOrCached()->find($this->request->ptp_length_limit_condition_id)->value,
                                $this->request->ptp_length_limit_value
                            );
                        });
            })->get([
                'id', 'article_num_main', 'article_num_reserve', 'article_num_archive', 'series_id',
                'dn_suction_id', 'dn_pressure_id', 'name', 'rated_power', 'rated_current', 'ptp_length',
                'sp_performance', 'dp_peak_performance', 'dp_standby_performance', 'pumpable_type'
            ])];
    }
}
