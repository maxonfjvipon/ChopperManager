<?php

namespace App\Models\Selections;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectionRange extends Model
{
    use HasFactory, HasTranslations;
    public $timestamps = false;
    public $translatable = ['name'];
    protected $guarded = [];

    public static int $CUSTOM = 3;
}
