<?php

namespace Modules\ProjectParticipant\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * User contractor link
 */
final class UserDealer extends Model
{
    use HasFactory, HasCompositePrimaryKey;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = "users_dealers";
    protected $primaryKey = ['user_id', 'dealer_id'];
    protected $guarded = [];
}
