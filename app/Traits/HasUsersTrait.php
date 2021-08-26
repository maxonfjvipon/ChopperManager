<?php

namespace App\Traits;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasUsersTrait
{
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
