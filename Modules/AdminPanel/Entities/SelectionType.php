<?php

namespace Modules\AdminPanel\Entities;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class SelectionType extends Model
{
    use HasFactory, UsesLandlordConnection, HasTranslations, SoftDeletes;

    protected $guarded = [];
    public $translatable = ['name'];
    public $timestamps = false;

    public function imgForTenant()
    {
        return Tenant::checkCurrent()
            ? (TenantAndSelectionType::where('tenant_id', Tenant::current()->id)
                    ->where('type_id', '=', $this->id)->first('img')->img ?? $this->default_img)
            : $this->default_img;
    }
}
