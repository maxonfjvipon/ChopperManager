<?php

namespace Modules\AdminPanel\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\AdminPanel\Events\TenantCreated;

class Tenant extends \Spatie\Multitenancy\Models\Tenant
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(TenantType::class);
    }

    protected static function booted()
    {
        static::created(fn(self $tenant) => $tenant->execute(fn(self $tenant) => event(new TenantCreated($tenant))));
    }
}
