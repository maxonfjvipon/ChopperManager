<?php

namespace Modules\User\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\Entities\Tenant;
use Modules\Pump\Entities\PumpBrand;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Discount extends Model
{
    use HasFactory, HasCompositePrimaryKey, UsesTenantConnection;

    public $timestamps = false;
    protected $fillable = ['user_id', 'value', 'discountable_id', 'discountable_type'];
    protected $primaryKey = ['discountable_id', 'user_id', 'discountable_type'];
    public $incrementing = false;

    public function discountable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'discountable_type', 'discountable_id');
    }

    private static function updateFromArrayForUserFor($type, $user, $array): int
    {
        self::whereUserId($user->id)
            ->whereDiscountableType($type)
            ->whereNotIn('discountable_id', $array)
            ->delete();
        return DB::table(Tenant::current()->database . '.discounts')->insertOrIgnore(array_map(fn($id) => [
            'user_id' => $user->id, 'discountable_type' => $type, 'discountable_id' => $id
        ], $array));

    }

    private static function updateFromRequestForUserForSeries(array $available_series_ids, $user): int
    {
        return self::updateFromArrayForUserFor('pump_series', $user, $available_series_ids);
    }

    private static function updateFromRequestForUserForBrand(array $available_series_ids, $user): int
    {
        return self::updateFromArrayForUserFor('pump_brand', $user, PumpBrand::whereHas('series', function ($query) use ($available_series_ids) {
            $query->whereIn('id', $available_series_ids)->select('id', 'brand_id');
        })->get('id')->pluck('id')->all());
    }

    public static function updateForUser(array $available_series_ids, Userable $user): bool
    {
        self::updateFromRequestForUserForSeries($available_series_ids, $user);
        self::updateFromRequestForUserForBrand($available_series_ids, $user);
        return true;
    }
}
