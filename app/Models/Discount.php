<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Discount extends Model
{
    use HasFactory, HasCompositePrimaryKey;
    public $timestamps = false;
    protected $fillable = ['user_id', 'value', 'discountable_id', 'discountable_type'];
    protected $primaryKey = ['discountable_id', 'user_id', 'discountable_type'];
    public $incrementing = false;

    public function discountable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'discountable_type', 'discountable_id');
    }
}
