<?php

namespace App\Models\users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
    use HasFactory;

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
