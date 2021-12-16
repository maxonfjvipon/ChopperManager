<?php


namespace Modules\Selection\Contracts;


interface PumpableSelectionServiceContract extends PumpableTypeSelectionContract
{
    public function selectionPropsResource(): array;
}
