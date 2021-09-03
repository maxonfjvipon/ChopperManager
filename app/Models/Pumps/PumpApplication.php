<?php

namespace App\Models\Pumps;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumpApplication extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;
}
