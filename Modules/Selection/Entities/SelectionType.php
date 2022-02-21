<?php

namespace Modules\Selection\Entities;

use App\Traits\Cached;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $pumpable_type
 * @property mixed $default_img
 * @property mixed $name
 */
final class SelectionType extends Model
{
    use HasFactory, HasTranslations, SoftDeletes, Cached;

    protected $guarded = [];
    public array $translatable = ['name'];
    public $timestamps = false;

    protected static function getCacheKey(): string
    {
        return "selection_types";
    }
}
