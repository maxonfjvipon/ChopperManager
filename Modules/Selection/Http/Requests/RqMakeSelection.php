<?php

namespace Modules\Selection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property array $main_pumps_counts
 * @property string $station_type
 * @property string $selection_type
 */
abstract class RqMakeSelection extends FormRequest
{
}
