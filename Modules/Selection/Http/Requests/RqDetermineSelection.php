<?php

namespace Modules\Selection\Http\Requests;

use BenSampo\Enum\Rules\EnumKey;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Selection\Entities\SelectionType;
use Modules\Selection\Entities\StationType;

/**
 * @property string $station_type
 * @property string $selection_type
 */
class RqDetermineSelection extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'station_type' => ['required', new EnumKey(StationType::class)],
            'selection_type' => ['required', new EnumKey(SelectionType::class)]
        ];
    }
}
