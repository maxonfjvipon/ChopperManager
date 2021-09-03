<?php

namespace App\Models\Pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class PumpCategory extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
