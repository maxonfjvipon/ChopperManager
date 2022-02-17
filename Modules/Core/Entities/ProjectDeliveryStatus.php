<?php

namespace Modules\Core\Entities;

use App\Traits\Cached;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Project delivery status.
 */
final class ProjectDeliveryStatus extends Model
{
    use HasFactory, UsesTenantConnection, HasTranslations, Cached;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;

    protected static function getCacheKey(): string
    {
        return "project_delivery_statuses";
    }
}
