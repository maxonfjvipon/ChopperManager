<?php

namespace Modules\AdminPanel\Support;

use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\TenantType;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

/**
 * Arrayable that returns specific selection types depending on the current tenant type
 * @package Modules\AdminPanel\Support
 */
class ArrTenantSelectionTypes implements Arrayable
{
    use UsesTenantModel;

    /**
     * Ctor wrap.
     * @return ArrTenantSelectionTypes
     */
    public static function new(): ArrTenantSelectionTypes
    {
        return new self();
    }

    /**
     * Ctor.
     */
    private function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        $tenantClass = $this->getTenantModel();
        if ($tenantClass::checkCurrent()) {
            $tenant = $tenantClass::current();
            $selectionTypes = match ($tenant->type->id) {
                TenantType::$PUMPPRODUCER => $tenant->selection_types,
                default => Auth::user()->available_selection_types
            };
        } else {
            $selectionTypes = SelectionType::all();
        }
        return ArrayableOf::items(...$selectionTypes)->asArray();
    }
}
