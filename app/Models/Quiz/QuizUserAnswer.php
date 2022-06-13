<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizUserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_user_session_id', 'quiz_question_id', 'quiz_answer_id',
    ];

    public function session() : BelongsTo
    {
        return $this->belongsTo(QuizUserSession::class);
    }

    public function question() : BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class);
    }

    public function answer() : BelongsTo
    {
        return $this->belongsTo(QuizAnswer::class, 'quiz_answer_id');
    }
}
