<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\QuizUserSession;
use App\Repositories\AbstractRepository;
use App\Repositories\BaseRepositoryTrait;
use Illuminate\Database\Eloquent\Model;

class QuizUserSessionRepository extends AbstractRepository
{
    use BaseRepositoryTrait;

    public function __construct(QuizUserSession $quizUserSession)
    {
        $this->model = $quizUserSession;
        $this->query = $quizUserSession->newQuery();
    }

    public function applyToken(string $token) : void
    {
        $this->query()->where('token', $token);
    }

    public function applyUserToken(string $token) : void
    {
        $this->query()->where('user_token', $token);
    }

    /**
     * @return QuizUserSession|Model|null
     */
    public function find(int $quizId, string $userToken, ?int $userId = null):? QuizUserSession
    {
        $this->applyUserToken($userToken);

        return $this->query()
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->orderByDesc('id')
            ->first();
    }
}
