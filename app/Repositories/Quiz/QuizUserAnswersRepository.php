<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizUserAnswer;
use App\Repositories\AbstractRepository;
use App\Repositories\BaseRepositoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuizUserAnswersRepository extends AbstractRepository
{
    use BaseRepositoryTrait;

    public function __construct(QuizUserAnswer $quizUserAnswer)
    {
        $this->model = $quizUserAnswer;
        $this->query = $quizUserAnswer->newQuery();
    }

    /**
     * @return QuizUserAnswer|Model
     */
    public function create(int $sessionId, int $questionId, int $answerId) : QuizUserAnswer
    {
        return $this->query()->firstOrCreate([
            'quiz_user_session_id' => $sessionId,
            'quiz_question_id' => $questionId,
            'quiz_answer_id' => $answerId,
        ]);
    }

    public function applySessionId(int $sessionId) : Builder
    {
        return $this->model()->newQuery()->where('quiz_user_session_id', $sessionId);
    }

    public function applyCorrectAnswers(?int $sessionId = null) : Builder
    {
        $query = $this->model()->newQuery();

        if ($sessionId) {
            $query->where('quiz_user_session_id', $sessionId);
        }

        return $query->with(['answer'])->whereHas('answer', function ($query) {
            return $query->where('is_correct', QuizAnswer::CORRECT_ANSWER);
        });
    }

    public function applyIncorrectAnswers(?int $sessionId = null) : Builder
    {
        $query = $this->model()->newQuery();

        if ($sessionId) {
            $query->where('quiz_user_session_id', $sessionId);
        }

        return $query->with(['answer'])->whereHas('answer', function ($query) {
            return $query->where('is_correct', QuizAnswer::INCORRECT_ANSWER);
        });
    }
}
