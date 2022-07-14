<?php

namespace Modules\Selection\Actions;

use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\RqStoreSelection;

/**
 * Update selection action.
 */
final class AcUpdateSelection
{
    /**
     * Ctor.
     *
     * @param RqStoreSelection $request
     * @param Selection        $selection
     */
    public function __construct(
        private RqStoreSelection $request,
        private Selection $selection,
    ) {
    }

    /**
     * @return void
     */
    public function __invoke()
    {
        $this->selection->project->updateTimestamps();
        $this->selection->update($this->request->selectionProps());
        $this->selection->pump_stations()
            ->whereNotIn(
                'id',
                array_map(
                    fn (array $station) => $station['id'],
                    $toUpdate = array_filter(
                        $this->request->added_stations,
                        fn (array $station) => (bool) $station['id']
                    )
                )
            )
            ->delete();
        $this->selection->pump_stations()->upsert(
            array_map(
                fn (array $station) => [
                    ...$station,
                    'selection_id' => $this->selection->id,
                    'updated_at' => now(),
                ],
                $toUpdate,
            ),
            ['id']
        );
        $this->selection->pump_stations()->insert(
            array_map(
                fn (array $station) => [
                    ...$station,
                    'selection_id' => $this->selection->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                array_filter(
                    $this->request->added_stations,
                    fn (array $station) => !$station['id']
                )
            )
        );
    }
}
