<?php

namespace Modules\Selection\Entities;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Selection.
 *
 * @property float $head
 * @property float $flow
 */
class Selection extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    public $timestamps = false;
    protected $guarded = [];
}
