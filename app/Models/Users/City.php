<?php

namespace App\Models\Users;

use App\Traits\HasUsersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $fillable = ['name', 'area_id'];
    public $timestamps = false;
    use HasFactory, HasUsersTrait;

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
