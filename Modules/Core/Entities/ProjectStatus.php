<?php

namespace Modules\Core\Entities;

use App\Traits\Cached;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Project status.
 */
final class ProjectStatus extends Model
{
    use HasFactory, UsesTenantConnection, HasTranslations, Cached;

    public array $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;

    protected static function getCacheKey(): string
    {
        return 'project_statuses';
    }
}
