<?php

namespace Modules\Selection\Entities;

use App\Traits\Cached;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * Selection range.
 */
final class SelectionRange extends Model
{
    use HasFactory, HasTranslations, UsesTenantConnection, Cached;

    protected static function getCacheKey(): string
    {
        return "selection_ranges";
    }

    public $timestamps = false;
    public array $translatable = ['name'];
    protected $guarded = [];

    public static int $CUSTOM = 3;
}
