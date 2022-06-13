<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    public const MODE_DEFAULT_BINARY = 'binary';
    public const MODE_MULTIPLE_CHOICE = 'multiple_choice';
    public const MODES = [
        self::MODE_DEFAULT_BINARY,
        self::MODE_MULTIPLE_CHOICE,
    ];

    use HasFactory;

    protected $fillable = [
        'mode', 'quiz_id', 'title',
    ];

    public function quiz() : BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers() : HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }
}
