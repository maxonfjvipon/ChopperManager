<?php

namespace Modules\AdminPanel\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spatie\Multitenancy\Models\Tenant;

class TenantCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Tenant $tenant;

    /**
     * Create a new event instance.
     *
     * @param $tenant
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }
}
