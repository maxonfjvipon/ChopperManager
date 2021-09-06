<?php

namespace App\Models\Users;

use App\Traits\HasTranslations;
use App\Traits\HasUsersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Country extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name', 'code', 'currency_id'];
    public $timestamps = false;
    use HasFactory, HasUsersTrait;
}
