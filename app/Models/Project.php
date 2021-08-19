<?php

namespace App\Models;

use App\Models\Selections\Single\SinglePumpSelection;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function selections(): HasMany
    {
        return $this->hasMany(SinglePumpSelection::class);
    }
}
