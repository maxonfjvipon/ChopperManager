<?php

namespace Modules\User\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

final class UserAndSelectionType extends Model
{
    use HasFactory, HasCompositePrimaryKey;

    protected $fillable = [];
    protected $table = 'users_and_selection_types';
    public $timestamps = false;
    protected $primaryKey = ['user_id', 'type_id'];
    public $incrementing = false;

    public static function updateAvailableSelectionTypesForUser(array $available_selection_type_ids, User $user): int
    {
        self::where('user_id', $user->id)
            ->whereNotIn('type_id', $available_selection_type_ids)
            ->delete();
        return DB::table('users_and_selection_types')->insertOrIgnore(array_map(fn($typeId) => [
            'user_id' => $user->id, 'type_id' => $typeId
        ], $available_selection_type_ids));
    }
}
