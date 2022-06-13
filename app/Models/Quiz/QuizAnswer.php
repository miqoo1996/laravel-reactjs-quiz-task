<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    use HasFactory;

    public const CORRECT_ANSWER = 1;
    public const INCORRECT_ANSWER = 0;

    protected $hidden = ['is_correct'];

    protected $fillable = [
        'quiz_question_id', 'is_correct', 'title',
    ];

    public function question() : BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}
