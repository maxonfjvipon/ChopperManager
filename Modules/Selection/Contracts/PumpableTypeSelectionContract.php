<?php

namespace Modules\Selection\Contracts;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Selection\Entities\Selection;

interface PumpableTypeSelectionContract
{
    public function createPath(): string;

    public function selectionResource(Selection $selection): JsonResource;
}
