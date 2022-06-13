<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\QuizStatistic;
use App\Repositories\AbstractRepository;
use App\Repositories\BaseRepositoryTrait;
use Illuminate\Database\Eloquent\Builder;

class QuizStatisticRepository extends AbstractRepository
{
    use BaseRepositoryTrait;

    public function __construct(QuizStatistic $quizStatistic)
    {
        $this->model = $quizStatistic;
        $this->query = $quizStatistic->newQuery();
    }

    public function applyDefaultOrder() : Builder
    {
        return $this->query()->orderByDesc('score')->orderByDesc('duration_left');
    }

    public function getQuizStatistics(bool $paginated = true)
    {
        $this->applyDefaultOrder();

        $this->applyRelations(['quiz', 'quizGuestUser']);

        return $this->fetchAll($paginated);
    }
}
