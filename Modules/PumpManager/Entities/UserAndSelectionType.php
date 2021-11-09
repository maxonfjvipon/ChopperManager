<?php

namespace Modules\PumpManager\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\Tenant;
use Modules\PumpManager\Http\Requests\UpdateUserRequest;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class UserAndSelectionType extends Model
{
    use HasFactory, UsesTenantConnection, HasCompositePrimaryKey;

    protected $fillable = [];
    protected $table = 'users_and_selection_types';
    public $timestamps = false;
    protected $primaryKey = ['user_id', 'type_id'];
    public $incrementing = false;

    public static function updateAvailableSelectionTypesForUserFromRequest(UpdateUserRequest $request, User $user): int
    {
        self::where('user_id', $user->id)
            ->whereNotIn('type_id', $request->available_selection_type_ids)
            ->delete();
        return DB::table(Tenant::current()->database . '.users_and_selection_types')->insertOrIgnore(array_map(fn($typeId) => [
            'user_id' => $user->id, 'type_id' => $typeId
        ], $request->available_selection_type_ids));
    }
}
