<?php

namespace App\Http\Controllers\Api\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\QuizAddGuestUserRequest;
use App\Http\Requests\Quiz\QuizSubmitAnswerRequest;
use App\Models\Quiz\QuizQuestion;
use App\Services\Quiz\QuizService;
use App\Services\Quiz\UserQuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class QuizController extends Controller
{
    private QuizService $quizService;
    private UserQuizService $userQuizService;

    public function __construct(QuizService $quizService, UserQuizService $userQuizService)
    {
        $this->quizService = $quizService;
        $this->userQuizService = $userQuizService;
    }

    public function index()
    {
        return $this->quizService->getListQuizzes(false, true);
    }

    public function get(int $id, string $mode = QuizQuestion::MODE_DEFAULT_BINARY) : array
    {
        return [
            'activeMode' => $mode,
            'modes' => QuizQuestion::MODES,
            'data' => $this->quizService->setQuizMode($mode, true, ['answers'])->fetchQuizById($id, true),
            'restart_session_text' => __('Changing the mode will restart and make a new session, Proceed with this action?'),
        ];
    }

    public function getSessionToken(int $id, string $userToken) : array
    {
        return $this->userQuizService->find($id, $userToken);
    }

    public function tokenization(Request $request, int $id, string $userToken) : array
    {
        abort_if(!in_array($request->activeMode, QuizQuestion::MODES), 422, 'Wrong mode was sent.');

        $this->userQuizService->setMode($request->activeMode);

        if (is_string($request->token)) {
            $this->userQuizService->setSessionToken($request->token);
        }

        if ($request->make !== true) {
            $tokenData = $this->userQuizService->find($id, $userToken);

            return $this->userQuizService->validityOfToken($this->userQuizService->find($id, $userToken))
                ? $tokenData
                : array_merge($this->userQuizService->sessionTokenDTO(), Arr::except($tokenData, 'duration'));
        }

        return $this->userQuizService->generateSessionToken($this->quizService->fetchQuizById($id, true), $userToken);
    }

    public function submitAnswer(QuizSubmitAnswerRequest $request, int $id, string $userToken) : JsonResponse
    {
        if (!$this->userQuizService->validityOfToken($this->userQuizService->find($id, $userToken))) {
            return response()->json(['error' => 'unprocessable token'], 422);
        }

        $tokenData = $this->userQuizService->setTokenDTOModelFields(['id'])->find($id, $userToken);

        $this->userQuizService->createUserAnswer($tokenData['id'], $request->question_id, $request->answer_id);

        $this->userQuizService->saveUserQuizStatistics($tokenData);

        return response()->json([
            'correct' => $this->quizService->getQuizAnswerRepository()->getCorrectAnswer($request->question_id),
            'is_correct' => $this->quizService->getQuizAnswerRepository()->isCorrectAnswer($request->answer_id),
        ]);
    }

    public function addGuestUser(QuizAddGuestUserRequest $request, int $id, string $userToken) : JsonResponse
    {
        if (!$this->userQuizService->validityOfToken($this->userQuizService->find($id, $userToken))) {
            return response()->json(['error' => 'unprocessable token'], 422);
        }

        $tokenData = $this->userQuizService->setTokenDTOModelFields(['id'])->find($id, $userToken);

        $this->userQuizService->saveGuestUser($tokenData['id'], $request->validated());

        return response()->json([
            'success' => true,
        ]);
    }

    public function getGuestUser(int $id, string $userToken) : JsonResponse
    {
        if (!$this->userQuizService->validityOfToken($this->userQuizService->find($id, $userToken))) {
            return response()->json(['error' => 'unprocessable token'], 422);
        }

        $tokenData = $this->userQuizService->setTokenDTOModelFields(['id'])->find($id, $userToken);

        return response()->json($this->userQuizService->getGuestUser($tokenData['id']));
    }
}
