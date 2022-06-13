<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\QuizQuestion;
use App\Repositories\AbstractRepository;
use App\Repositories\BaseRepositoryTrait;
use Illuminate\Database\Eloquent\Builder;

class QuizQuestionRepository extends AbstractRepository
{
    use BaseRepositoryTrait;

    public function __construct(QuizQuestion $quizQuestion)
    {
        $this->model = $quizQuestion;
        $this->query = $quizQuestion->newQuery();
    }

    public function applyQuizId($id): Builder
    {
        return $this->query->where('quiz_id', $id);
    }

    public function applyDefaultOrder() : Builder
    {
        return $this->query()->orderByDesc('quiz_id')->orderByDesc('id');
    }

    public function getQuizQuestionsCountByQuizId($quizId, string $mode) : int
    {
        return $this->applyQuizId($quizId)->where('mode', $mode)->count();
    }
}
