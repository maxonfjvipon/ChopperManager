<?php

namespace Modules\Selection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property array<int> $main_pumps_counts
 * @property string $station_type
 * @property string $selection_type
 * @property float $flow
 * @property float $head
 * @property int $reserve_pumps_count
 * @property array<int> $control_system_type_ids
 * @property array<string> $collectors
 * @property int $pump_id
 * @property string $collector
 * @property int $main_pumps_count
 */
abstract class RqMakeSelection extends FormRequest
{
}
