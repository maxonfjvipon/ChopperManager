<?php

namespace Modules\Selection\Http\Requests;

use App\Rules\ArrayExistsInArray;
use JetBrains\PhpStorm\Pure;
use Modules\Components\Entities\ControlSystemType;
use Modules\Selection\Rules\PumpStationsArray;

/**
 * @property float        $flow
 * @property float        $head
 * @property array<array> $added_stations
 * @property int          $reserve_pumps_count
 * @property string       $comment
 * @property array<int>   $control_system_type_ids
 * @property string       $selection
 */
abstract class RqStoreSelection extends RqDetermineSelection
{
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'flow' => ['required', 'numeric', 'min:0'],
                'head' => ['required', 'numeric', 'min:0'],
                'reserve_pumps_count' => ['required', 'numeric', 'in:0,1,2,3,4'],
                'control_system_type_ids' => ['required', 'array', new ArrayExistsInArray(ControlSystemType::allOrCached()->pluck('id')->all())],
                'added_stations' => ['required', 'array', new PumpStationsArray()],
                'comment' => ['sometimes', 'nullable', 'string'],
            ]
        );
    }

    private string $separator = ',';

    #[Pure]
 protected function imploded($array): ?string
 {
     return $array ? implode($this->separator, $array) : null;
 }

    public function selectionProps(): array
    {
        return [
            'flow' => $this->flow,
            'head' => $this->head,
            'reserve_pumps_count' => $this->reserve_pumps_count,
            'control_system_type_ids' => $this->imploded($this->control_system_type_ids),
            'comment' => $this->comment,
        ];
    }
}
