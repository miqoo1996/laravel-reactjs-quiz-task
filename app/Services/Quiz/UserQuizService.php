<?php

namespace App\Services\Quiz;

use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizGuestUser;
use App\Models\Quiz\QuizStatistic;
use App\Models\Quiz\QuizUserAnswer;
use App\Models\Quiz\QuizUserSession;
use App\Repositories\Quiz\QuizAnswerRepository;
use App\Repositories\Quiz\QuizGuestUserRepository;
use App\Repositories\Quiz\QuizQuestionRepository;
use App\Repositories\Quiz\QuizStatisticRepository;
use App\Repositories\Quiz\QuizUserAnswersRepository;
use App\Repositories\Quiz\QuizUserSessionRepository;
use App\Services\AbstractService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserQuizService extends AbstractService
{
    public const DEFAULT_SESSION_TOKEN_LENGTH = 60;

    private QuizUserSessionRepository $quizUserSessionRepository;
    private QuizQuestionRepository $quizQuestionRepository;
    private QuizUserAnswersRepository $quizUserAnswersRepository;
    private QuizGuestUserRepository $quizGuestUserRepository;
    private QuizStatisticRepository $quizStatisticRepository;

    protected ?string $sessionToken = null;
    protected ?string $mode = null;
    protected ?array $tokenDTOModelFields = [];

    public function __construct(
        QuizUserSessionRepository $quizUserSessionRepository,
        QuizQuestionRepository $quizQuestionRepository,
        QuizUserAnswersRepository $quizUserAnswersRepository,
        QuizGuestUserRepository $quizGuestUserRepository,
        QuizStatisticRepository $quizStatisticRepository
    )
    {
        $this->quizUserSessionRepository = $quizUserSessionRepository;
        $this->quizUserAnswersRepository = $quizUserAnswersRepository;
        $this->quizGuestUserRepository = $quizGuestUserRepository;
        $this->quizStatisticRepository = $quizStatisticRepository;
        $this->quizQuestionRepository = $quizQuestionRepository;
    }

    public function getQuizUserSessionRepository(): QuizUserSessionRepository
    {
        return $this->quizUserSessionRepository;
    }

    public function getUserAnswersRepository(): QuizUserAnswersRepository
    {
        return $this->quizUserAnswersRepository;
    }

    public function getQuizGuestUserRepository(): QuizGuestUserRepository
    {
        return $this->quizGuestUserRepository;
    }

    public function getQuizStatisticRepository(): QuizStatisticRepository
    {
        return $this->quizStatisticRepository;
    }

    public function getQuizAnswersRepository(): QuizAnswerRepository
    {
        return $this->quizAnswersRepository;
    }

    public function getSessionToken(): ?string
    {
        return $this->sessionToken;
    }

    public function setSessionToken(string $sessionToken): self
    {
        $this->sessionToken = $sessionToken;

        return $this;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function setTokenDTOModelFields(?array $tokenDTOModelFields): self
    {
        $this->tokenDTOModelFields = $tokenDTOModelFields;

        return $this;
    }

    public function sessionTokenDTO(?QuizUserSession $quizUserSession = null) : array
    {
        $exists = !empty($quizUserSession);
        $expired = $exists && now()->gt($quizUserSession->expires_at);

        return array_merge([
            'exists' => $exists,
            'expired' => $expired,
            'token' => optional($quizUserSession)->token,
            'expires_at' => optional($quizUserSession)->expires_at,
            'duration' => $exists && !$expired ? now()->diffInMilliseconds($quizUserSession->expires_at) : 0,
        ], $quizUserSession ? Arr::only($quizUserSession->getAttributes(), $this->tokenDTOModelFields) : []);
    }

    public function find(int $quizId, string $userToken, ?int $userId = null): array
    {
        if (!empty($this->getSessionToken())) {
            $this->quizUserSessionRepository->applyToken($this->getSessionToken());
        }

        return $this->sessionTokenDTO($this->quizUserSessionRepository->find($quizId, $userToken, $userId ?: auth()->id()));
    }

    public function generateSessionToken(Quiz $quiz, string $userToken, ?int $userId = null, int $length = self::DEFAULT_SESSION_TOKEN_LENGTH) : array
    {
        $expiresAt = Carbon::now()->addMinutes($quiz->duration)->toDateTimeString();

        $data = [
            'mode' => $this->mode,
            'quiz_id' => $quiz->id,
            'user_id' => $userId ?: auth()->id(),
            'user_token' => $userToken,
            'token' => Str::random($length),
            'expires_at' => $expiresAt,
        ];

        return $this->sessionTokenDTO($this->quizUserSessionRepository->query()->create($data));
    }

    public function validityOfToken(array $sessionTokenDTO) : bool
    {
        return !empty($sessionTokenDTO['token']) && $sessionTokenDTO['duration'] > 0;
    }

    public function createUserAnswer(int $sessionId, int $questionId, int $answerId, bool $returnCorrect = false) : QuizUserAnswer
    {
        return $this->quizUserAnswersRepository->create($sessionId, $questionId, $answerId);
    }

    /**
     * @return QuizGuestUser|Model|null
     */
    public function getGuestUser(int $sessionId) :? QuizGuestUser
    {
        return $this->quizGuestUserRepository->applyWhereConditions(['quiz_user_session_id' => $sessionId])->first();
    }

    /**
     * @return QuizGuestUser|Model
     */
    public function saveGuestUser(int $sessionId, array $data) : QuizGuestUser
    {
        $data['quiz_user_session_id'] = $sessionId;

        $model = $this->getGuestUser($sessionId) ?: $this->quizGuestUserRepository->model();

        $model->fill($data)->save();

        return $model;
    }

    public function getQuizStatistics(bool $paginated = true)
    {
        return $this->quizStatisticRepository->getQuizStatistics($paginated);
    }

    /**
     * @return QuizStatistic|Model|null
     */
    public function saveUserQuizStatistics(array $tokenData) :? QuizStatistic
    {
        $session = $this->quizUserSessionRepository->fetchById($tokenData['id']);
        $guestUser = $this->quizGuestUserRepository->fetchBySessionId($tokenData['id']);

        if (empty($guestUser)) return null;

        $quizId = $session->quiz_id;
        $submitDate = (new Carbon())->now();

        $quizQuestionsCount = $this->quizQuestionRepository->getQuizQuestionsCountByQuizId($quizId, $session->mode);
        $submittedCount = $this->quizUserAnswersRepository->applySessionId($tokenData['id'])->count();
        $submittedCorrectAnswersCount = $this->quizUserAnswersRepository->applyCorrectAnswers($tokenData['id'])->count();
        $unansweredCount = $quizQuestionsCount - $submittedCount;

        $score = $quizQuestionsCount ? floor(($submittedCorrectAnswersCount / $quizQuestionsCount) * 100) : 0;

        $isEnded = $tokenData['expired'] ?: $unansweredCount === 0;

        $model = $this->quizStatisticRepository->query()->where([
            'quiz_id' => $quizId,
            'quiz_guest_user_id' => $guestUser->id,
        ])->first() ?: $this->quizStatisticRepository->model();

        $model->fill([
            'quiz_id' => $quizId,
            'quiz_guest_user_id' => $guestUser->id,
            'is_ended' => $isEnded,
            'unanswered_count' => $unansweredCount,
            'score' => intval($score),
            'submit_date' => $submitDate,
            'duration_left' => $submitDate->diffInMilliseconds($session->expires_at),
        ])->save();

        return $model;
    }
}
