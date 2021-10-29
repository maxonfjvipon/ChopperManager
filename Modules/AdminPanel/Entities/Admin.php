<?php

namespace Modules\AdminPanel\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, UsesLandlordConnection;

    protected $fillable = ['login', 'password'];
    protected $hidden = ['password'];
    protected $table = 'admins';
    public $timestamps = false;

}
