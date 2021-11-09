<?php

namespace Modules\AdminPanel\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\AdminPanel\Events\TenantCreated;
use Modules\AdminPanel\Http\Requests\StoreTenantRequest;
use Modules\AdminPanel\Http\Requests\UpdateTenantRequest;
use Modules\AdminPanel\Traits\HasTenantSpecificControllers;
use Modules\User\Traits\UsesUserModel;
use Nwidart\Modules\Facades\Module;

class Tenant extends \Spatie\Multitenancy\Models\Tenant
{
    use HasFactory, HasTenantSpecificControllers, UsesUserModel;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'has_registration' => 'boolean',
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];


    public function getModuleAttribute()
    {
        return $this->type->module_name;
    }

    // RELATIONSHIPS
    public function selection_types(): BelongsToMany
    {
        return $this->belongsToMany(SelectionType::class, 'tenants_and_selection_types', 'tenant_id', 'type_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TenantType::class);
    }

    // STATIC
    public static function createFromRequest(StoreTenantRequest $request)
    {
        $tenant = self::create($request->tenantProps());
        if ($tenant) {
            TenantAndSelectionType::createFromRequestForTenant($request, $tenant);
            $tenant->execute(fn(self $tenant) => event(new TenantCreated($tenant)));
        }
    }

    public function updateFromRequest(UpdateTenantRequest $request): bool
    {
        $updated = $this->update($request->tenantProps());
        if ($updated && $this->type_id != TenantType::$PUMPMANAGER) {
            TenantAndSelectionType::updateFromRequestForTenant($request, $this);
        }
        return $updated;
    }

    // FUNCTIONS
    public function getControllerClass($controllerName): string
    {
        return "Modules\\" . $this->getModuleName() . "\\Http\\Controllers\\" . $controllerName;
    }

    public function getUserClass(): string
    {
        return "Modules\\" . $this->getModuleName() . "\\Entities\\User";
    }

    public function getModuleName()
    {
        return Module::find($this->module)->getName();
    }

    public function getGuard()
    {
        return $this->module;
    }

}
