<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\QuizGuestUser;
use App\Repositories\AbstractRepository;
use App\Repositories\BaseRepositoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class QuizGuestUserRepository extends AbstractRepository
{
    use BaseRepositoryTrait;

    public function __construct(QuizGuestUser $quizGuestUser)
    {
        $this->model = $quizGuestUser;
        $this->query = $quizGuestUser->newQuery();
    }

    public function applyDefaultOrder() : Builder
    {
        return $this->query()->orderByDesc('id');
    }

    public function getUsersQuizHistory(bool $hasAnswers = false) : Collection
    {
        $this->applyRelations(['quiz.answers', 'session', 'userAnswers'])->orderByDesc('quiz_user_session_id');

        if ($hasAnswers) {
            $this->query()->has('userAnswers');
        }

        return $this->query()->get();
    }

    /**
     * @param $id
     * @return QuizGuestUser|Model|null
     */
    public function fetchBySessionId($id): ?QuizGuestUser
    {
        return $this->query()->where('quiz_user_session_id', $id)->first();
    }
}
