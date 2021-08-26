<?php

namespace App\Models\Users;

use App\Traits\HasUsersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory, HasUsersTrait;
}
