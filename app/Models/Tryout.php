<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tryout extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'package_id', 'duration', 'started_at', 'finished_at'
    ];

    public function TryoutAnswers(): HasMany
    {
        return $this->hasMany(TryoutAnswer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
