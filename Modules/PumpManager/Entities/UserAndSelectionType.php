<?php

namespace Modules\PumpManager\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\Tenant;
use Modules\PumpManager\Http\Requests\PMUpdateUserRequest;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class UserAndSelectionType extends Model
{
    use HasFactory, UsesTenantConnection, HasCompositePrimaryKey;

    protected $fillable = [];
    protected $table = 'users_and_selection_types';
    public $timestamps = false;
    protected $primaryKey = ['user_id', 'type_id'];
    public $incrementing = false;

    public static function updateAvailableSelectionTypesForUser(array $available_selection_type_ids, PMUser $user): int
    {
        self::where('user_id', $user->id)
            ->whereNotIn('type_id', $available_selection_type_ids)
            ->delete();
        return DB::table(Tenant::current()->database . '.users_and_selection_types')->insertOrIgnore(array_map(fn($typeId) => [
            'user_id' => $user->id, 'type_id' => $typeId
        ], $available_selection_type_ids));
    }
}
