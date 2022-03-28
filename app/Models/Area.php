<?php

namespace App\Models;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Area.
 *
 * @property string $name
 */
class Area extends Model
{
    use HasFactory, Cached;

    public $timestamps = false;
    protected $guarded = [];

    protected static function getCacheKey(): string
    {
        return 'areas';
    }
}