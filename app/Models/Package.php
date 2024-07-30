<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'duration', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function packageQuestions(): HasMany
    {
        return $this->hasMany(PackageQuestion::class);
    }
}
