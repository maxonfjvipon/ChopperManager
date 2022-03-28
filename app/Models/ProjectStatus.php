<?php

namespace App\Models;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Project status.
 */
class ProjectStatus extends Model
{
    use HasFactory, Cached;

    protected $table = "project_statuses";
    public $timestamps = false;
    protected $guarded = [];
}
