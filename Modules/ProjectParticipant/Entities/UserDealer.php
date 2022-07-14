<?php

namespace Modules\ProjectParticipant\Entities;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * User contractor link.
 */
final class UserDealer extends Model
{
    use HasFactory;
    use HasCompositePrimaryKey;

    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'users_dealers';

    protected $primaryKey = ['user_id', 'dealer_id'];

    protected $guarded = [];
}
