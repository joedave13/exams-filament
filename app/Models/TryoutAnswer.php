<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TryoutAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'tryout_id', 'question_id', 'question_option_id', 'score'
    ];

    public function tryout(): BelongsTo
    {
        return $this->belongsTo(Tryout::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function questionOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class);
    }
}
