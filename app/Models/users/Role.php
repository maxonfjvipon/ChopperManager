<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends HasUsersModel
{
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
