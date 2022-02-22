<?php

namespace Modules\Project\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Currency.
 * @property string $code
 * @property string $name
 * @property int $id
 */
final class Currency extends Model
{
    use HasFactory, Cached;

    protected $fillable = ['name', 'code'];
    public $timestamps = false;

    protected static function getCacheKey(): string
    {
        return "currencies";
    }

    public function getCodeNameAttribute(): string
    {
        return "{$this->code} / {$this->name}";
    }
}
