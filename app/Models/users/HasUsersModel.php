<?php


namespace App\Models\Users;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasUsersModel extends Model
{
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
