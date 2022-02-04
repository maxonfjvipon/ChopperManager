<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

// With or without trashed depend on is user admin or not
trait WithOrWithoutTrashed
{
    public static function withOrWithoutTrashed()
    {
        return self::isUserAdmin()
            ? self::withTrashed()
            : self::withoutTrashed();
    }

    public function scopeWithOrWithoutTrashed($query)
    {
        $query->when(self::isUserAdmin(), function ($query) {
            $query->withTrashed();
        });
    }

    private static function isUserAdmin(): bool
    {
        return Auth::user()->isAdmin();
    }
}
