<?php

namespace Modules\User\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * User contractor link
 */
final class UserContractor extends Model
{
    use HasFactory, HasCompositePrimaryKey;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = "users_contractors";
    protected $primaryKey = ['user_id', 'contractor_id'];
    protected $guarded = [];
}
