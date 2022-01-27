<?php

namespace Modules\User\Traits;

use Modules\AdminPanel\Entities\TenantType;
use Modules\PumpManager\Entities\PMUser;
use Modules\PumpProducer\Entities\PPUser;
use Modules\User\Entities\Userable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

/**
 * Trait HasUserable
 * @package Modules\User\Traits
 */
trait HasUserable
{

    use UsesTenantModel;

    /**
     * @param $props
     * @return Userable
     */
    public function createdUser($props): Userable
    {
        return (new ($this->getUserClass()))->create($props);
    }

    /**
     * @return string
     */
    protected function getUserClass(): string
    {
        return match ($this->getTenantModel()::current()->type->id) {
            default => PMUser::class,
            TenantType::$PUMPPRODUCER => PPUser::class,
        };
    }
}
