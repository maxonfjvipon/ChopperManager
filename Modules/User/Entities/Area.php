<?php

namespace Modules\User\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Area.
 *
 * @property int $id
 * @property string $name
 */
final class Area extends Model
{
    use HasFactory, Cached;

    public $timestamps = false;
    protected $guarded = [];

    /**
     * @return string
     */
    protected static function getCacheKey(): string
    {
        return 'areas';
    }
}
