<?php

namespace Modules\AdminPanel\Entities;

use App\Traits\Cached;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

/**
 * @property mixed $pumpable_type
 * @property mixed $default_img
 * @property mixed $name
 */
class SelectionType extends Model
{
    use HasFactory, UsesLandlordConnection, HasTranslations, SoftDeletes, Cached;

    protected $guarded = [];
    public $translatable = ['name'];
    public $timestamps = false;

    protected static function getCacheKey(): string
    {
        return "selection_types";
    }

    public function imgForTenant()
    {
        return Tenant::checkCurrent()
            ? (TenantAndSelectionType::where('tenant_id', Tenant::current()->id)
                    ->where('type_id', '=', $this->id)->first('img')->img ?? $this->default_img)
            : $this->default_img;
    }
}
