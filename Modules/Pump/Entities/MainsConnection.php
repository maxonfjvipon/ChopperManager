<?php

namespace Modules\Pump\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Mains connection
 */
final class MainsConnection extends Model
{
    use HasFactory, Cached;

    protected $fillable = ['phase', 'voltage'];
    public $timestamps = false;

    protected static function getCacheKey(): string
    {
        return "mains_connections";
    }

    protected $appends = ['full_value'];

    public function getFullValueAttribute()
    {
        return "{$this->phase}({$this->voltage})";
    }

}
