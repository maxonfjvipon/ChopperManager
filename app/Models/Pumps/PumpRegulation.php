<?php

namespace App\Models\Pumps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class PumpRegulation extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

}
