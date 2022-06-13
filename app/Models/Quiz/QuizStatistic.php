<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizStatistic extends Model
{
    use HasFactory;

    protected $appends = ['used_time_mins'];

    protected $fillable = [
        'quiz_id', 'quiz_guest_user_id', 'is_ended', 'unanswered_count', 'score', 'duration_left', 'submit_date',
    ];

    public function quizGuestUser() : BelongsTo
    {
        return $this->belongsTo(QuizGuestUser::class, 'quiz_guest_user_id');
    }

    public function quiz() : BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function getUsedTimeMinsAttribute() : int
    {
        return $this->quiz->duration - ($this->duration_left /  (60 * 1000));
    }
}
