<?php

namespace Modules\Selection\Services;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Selection\Contracts\PumpableSelectionServiceContract;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Services\PumpType\PumpableTypeSelectionService;

abstract class PumpableSelectionService implements PumpableSelectionServiceContract
{
    private PumpableTypeSelectionService $typeService;

    public function __construct(PumpableTypeSelectionService $service)
    {
        $this->typeService = $service;
    }

    public function createPath(): string
    {
        return $this->typeService->createPath();
    }

    public function selectionResource(Selection $selection): JsonResource
    {
        return $this->typeService->selectionResource($selection);
    }
}
