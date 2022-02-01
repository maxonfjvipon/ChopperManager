<?php

namespace Modules\AdminPanel\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Modules\AdminPanel\Events\TenantCreated;
use Modules\AdminPanel\Http\Requests\StoreTenantRequest;
use Modules\AdminPanel\Http\Requests\UpdateTenantRequest;

class Tenant extends \Spatie\Multitenancy\Models\Tenant
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'has_registration' => 'boolean',
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];

    public function getTypeCacheKey(): string
    {
        return 'tenant_type_for_' . $this->id;
    }

    public function getTypeAttribute()
    {
        $type = Cache::rememberForever($this->getTypeCacheKey(), function () {
            return $this->getRelationValue('type');
        });
        $this->setRelation('type', $type);
        return $type;
    }

    public function getGuardAttribute()
    {
        return $this->type->guard;
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
        Cache::forget($this->getTypeCacheKey());
        return $updated;
    }
}
