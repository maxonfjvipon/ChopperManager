<?php

namespace App\Models;

use App\Traits\HasArea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Organization.
 *
 * @property int $id
 * @property string $name
 * @property string $itn
 */
class Organization extends Model
{
    use HasFactory, HasArea;

    protected $table = "organizations";
    public $timestamps = false;
    protected $guarded = [];
}
