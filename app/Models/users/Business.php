<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends HasUsersModel
{
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
