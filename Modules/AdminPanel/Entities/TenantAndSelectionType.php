<?php

namespace Modules\AdminPanel\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Http\Requests\StoreTenantRequest;
use Modules\AdminPanel\Http\Requests\UpdateTenantRequest;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class TenantAndSelectionType extends Model
{
    use HasFactory, UsesLandlordConnection, HasCompositePrimaryKey;

    protected $guarded = [];
    protected $table = "tenants_and_selection_types";
    public $timestamps = false;
    protected $primaryKey = ['tenant_id', 'type_id'];
    public $incrementing = false;

    public static function createFromRequestForTenant(StoreTenantRequest $request, $tenant): int
    {
        return DB::table('tenants_and_selection_types')
            ->insertOrIgnore(array_map(function ($selection_type_id) use ($tenant) {
                return [
                    'tenant_id' => $tenant->id,
                    'type_id' => $selection_type_id,
                    'img' => SelectionType::find($selection_type_id)->default_img
                ];
            }, $request->selection_type_ids));
    }

    public static function updateFromRequestForTenant(UpdateTenantRequest $request, $tenant)
    {
        if ($request->selection_type_ids) {
            self::whereTenantId($tenant->id)
                ->whereNotIn('type_id', $request->selection_type_ids)
                ->delete();
            return self::createFromRequestForTenant($request, $tenant);
        }
    }
}
