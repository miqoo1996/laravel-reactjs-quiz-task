<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class QuizGuestUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_user_session_id', 'firstname', 'lastname', 'email'
    ];

    public function quiz() : HasOneThrough
    {
        return $this->hasOneThrough(Quiz::class, QuizUserSession::class, 'id', 'id', 'quiz_user_session_id', 'quiz_id');
    }

    public function session() : BelongsTo
    {
        return $this->belongsTo(QuizUserSession::class, 'quiz_user_session_id');
    }

    public function userAnswers() : HasMany
    {
        return $this->hasMany(QuizUserAnswer::class, 'quiz_user_session_id', 'quiz_user_session_id');
    }
}
