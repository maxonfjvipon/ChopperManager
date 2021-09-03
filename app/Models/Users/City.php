<?php

namespace App\Models\Users;

use App\Traits\HasUsersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasTranslations;

class City extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name', 'area_id'];
    public $timestamps = false;
    use HasFactory, HasUsersTrait;

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
