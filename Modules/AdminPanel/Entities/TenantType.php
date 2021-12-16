<?php

namespace Modules\AdminPanel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantType extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['id', 'name', 'module_name'];

    public static int $PUMPMANAGER = 1;
    public static int $PUMPPRODUCER = 2;

    public static function all($columns = ['*'])
    {
        return parent::where('id', "!=", self::$PUMPMANAGER)->get($columns)->all();
    }
}
