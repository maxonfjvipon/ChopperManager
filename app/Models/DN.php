<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class DN extends Model
{
    protected $fillable = ['value'];
    protected $table = 'dns';
    public $timestamps = false;
    use HasFactory, UsesTenantConnection;
}
