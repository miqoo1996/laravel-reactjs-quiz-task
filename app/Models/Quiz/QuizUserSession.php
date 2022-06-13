<?php

namespace App\Models\Quiz;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizUserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id', 'user_id', 'mode', 'user_token', 'token', 'expires_at',
    ];

    public function quiz() : BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
