<?php

namespace Modules\User\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Contractor.
 */
final class Contractor extends Model
{
    use HasFactory, Cached;

    protected $guarded = [];

    /**
     * @return string
     */
    protected static function getCacheKey(): string
    {
        return "contractors";
    }
}
