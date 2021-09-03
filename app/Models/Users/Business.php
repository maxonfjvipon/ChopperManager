<?php

namespace App\Models\Users;

use App\Traits\HasUsersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Business extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory, HasUsersTrait;
}
