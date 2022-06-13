<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Quiz extends Model
{
    public const DEFAULT_DURATION = 5;

    use HasFactory;

    protected $fillable = [
        'duration', 'title', 'description',
    ];

    public function questions() : HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function answers() : hasManyThrough
    {
        return $this->hasManyThrough(QuizAnswer::class, QuizQuestion::class);
    }
}
