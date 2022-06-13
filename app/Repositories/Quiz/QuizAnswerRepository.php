<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\QuizAnswer;
use App\Repositories\AbstractRepository;
use App\Repositories\BaseRepositoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuizAnswerRepository extends AbstractRepository
{
    use BaseRepositoryTrait;

    public function __construct(QuizAnswer $quizAnswer)
    {
        $this->model = $quizAnswer;
        $this->query = $quizAnswer->newQuery();
    }

    public function applyDefaultOrder() : Builder
    {
        return $this->query()->orderByDesc('quiz_question_id')->orderByDesc('id');
    }

    public function applyQuizQuestionId($id): Builder
    {
        return $this->query()->where('quiz_question_id', $id);
    }

    public function applyCorrectAnswer(): Builder
    {
        return $this->query()->where('is_correct', QuizAnswer::CORRECT_ANSWER);
    }

    public function applyIncorrectAnswer(): Builder
    {
        return $this->query()->where('is_correct', QuizAnswer::INCORRECT_ANSWER);
    }

    /**
     * @return QuizAnswer|Model|null
     */
    public function getCorrectAnswer($questionId) :? QuizAnswer
    {
        $this->applyQuizQuestionId($questionId);
        $this->applyCorrectAnswer();

        return $this->query()->first();
    }

    public function isCorrectAnswer($id) : bool
    {
        return $this->applyCorrectAnswer()->where('id', $id)->exists();
    }

    public function isIncorrectAnswer($id) : bool
    {
        return $this->applyIncorrectAnswer()->where('id', $id)->exists();
    }
}
