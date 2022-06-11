<?php

namespace Modules\Selection\Actions;

use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\RqStoreSelection;

final class AcUpdateSelection
{
    public function __construct(
        private RqStoreSelection $request,
        private Selection $selection,
    )
    {
    }

    public function __invoke()
    {
        $this->selection->update($this->request->selectionProps());
        $toUpdate = array_filter(
            $this->request->added_stations,
            fn(array $station) => !!$station['id']
        );
        $this->selection->pump_stations()
            ->whereNotIn(
                'id',
                array_map(
                    fn(array $station) => $station['id'],
                    $toUpdate
                )
            )
            ->delete();
        $this->selection->pump_stations()->upsert(
            array_map(
                fn(array $station) => [
                    ...$station,
                    'selection_id' => $this->selection->id
                ],
                $toUpdate,
            ),
            ['id']
        );
        $this->selection->pump_stations()->insert(
            array_map(
                fn(array $station) => [
                    ...$station,
                    'selection_id' => $this->selection->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                array_filter(
                    $this->request->added_stations,
                    fn(array $station) => !$station['id']
                )
            )
        );
    }
}
