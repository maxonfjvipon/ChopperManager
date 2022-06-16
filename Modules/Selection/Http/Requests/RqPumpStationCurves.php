<?php

namespace Modules\Selection\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\In;
use Modules\Pump\Entities\Pump;

/**
 * @property int $pump_id
 * @property int $main_pumps_count
 * @property int $reserve_pumps_count
 * @property float $flow
 * @property float $head
 * @property int $jockey_pump_id
 * @property float $jockey_flow
 * @property float $jockey_head
 */
final class RqPumpStationCurves extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws Exception
     */
    public function rules(): array
    {
        return [
            'pump_id' => ['required', new In($pumpIds = Pump::allOrCached()->pluck('id')->all())],
            'main_pumps_count' => ['required', 'numeric'],
            'reserve_pumps_count' => ['required', 'numeric'],
            'head' => ['sometimes', 'nullable', 'numeric'],
            'flow' => ['sometimes', 'nullable', 'numeric'],

            'jockey_pump_id' => ['sometimes', 'nullable', new In($pumpIds)],
            'jockey_flow' => ['sometimes', 'nullable', 'numeric'],
            'jockey_head' => ['sometimes', 'nullable', 'numeric']
        ];
    }

    /**
     * @return array
     */
    public function mainStationProps(): array
    {
        return $this->stationProps(
            $this->pump_id,
            $this->main_pumps_count,
            $this->reserve_pumps_count,
            $this->flow,
            $this->head
        );
    }

    /**
     * @return array
     */
    public function jockeyPumpProps(): array
    {
        return $this->stationProps(
            $this->jockey_pump_id,
            1,
            0,
            $this->jockey_flow,
            $this->jockey_head
        );
    }

    /**
     * @return bool
     */
    public function hasJockey(): bool
    {
        return $this->jockey_pump_id && $this->jockey_flow && $this->jockey_head;
    }

    /**
     * @param int $pumpId
     * @param int $mainPumpsCount
     * @param int $reservePumpsCount
     * @param float $flow
     * @param float $head
     * @return array
     */
    private function stationProps(
        int   $pumpId,
        int   $mainPumpsCount,
        int   $reservePumpsCount,
        float $flow,
        float $head,
    ): array
    {
        return [
            'pump_id' => $pumpId,
            'main_pumps_count' => $mainPumpsCount,
            'reserve_pumps_count' => $reservePumpsCount,
            'flow' => $flow,
            'head' => $head
        ];
    }
}
