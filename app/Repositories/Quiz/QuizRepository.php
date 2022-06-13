<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\Quiz;
use App\Repositories\AbstractRepository;
use App\Repositories\BaseRepositoryTrait;
use Illuminate\Database\Eloquent\Builder;

class QuizRepository extends AbstractRepository
{
    use BaseRepositoryTrait;

    public function __construct(Quiz $quiz)
    {
        $this->model = $quiz;
        $this->query = $quiz->newQuery();
    }

    public function applyDefaultOrder() : Builder
    {
        return $this->query()->orderByDesc('id');
    }

    public function applyQuestionMode(?string $mode, array $questionRelations = []) : self
    {
        $this->query()->with(['questions' => function($builder) use($mode, $questionRelations) {
            $builder->where('mode', $mode)->when(!empty($questionRelations), fn ($builder) => $builder->with($questionRelations));
        }]);

        return $this;
    }
}
