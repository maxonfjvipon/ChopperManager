<?php

namespace Modules\AdminPanel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantType extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['id', 'name'];

}
