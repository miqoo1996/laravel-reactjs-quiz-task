<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\ProcessQuizQuestionRequest;
use App\Services\Quiz\QuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuizQuestionController extends Controller
{
    private QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function index($quizId) : View
    {
        abort_if(!$this->quizService->getQuizRepository()->applyId($quizId)->exists(), 404);

        $list = $this->quizService->getListQuizQuestions($quizId, true, true, ['quiz']);

        return view('admin.quiz.question.index', compact('quizId', 'list'));
    }

    public function create($quizId) : View
    {
        abort_if(!$this->quizService->getQuizRepository()->applyId($quizId)->exists(), 404);

        $model = $this->quizService->getQuizQuestionRepository()->model(['quiz_id' => $quizId]);

        return view('admin.quiz.question.create', compact('model'));
    }

    public function update($id) : View
    {
        $model = $this->quizService->fetchQuizQuestionById($id, true);

        return view('admin.quiz.question.update', compact('model'));
    }

    public function save($id = null, ProcessQuizQuestionRequest $processQuizQuestionRequest) : RedirectResponse
    {
        $this->quizService->fetchQuizQuestionById($id, false, true);

        $this->quizService->getQuizQuestionRepository()->save($processQuizQuestionRequest->validated());

        return redirect()->route('admin.quiz.question.index', $processQuizQuestionRequest->quizId)->with('success', 'Successfully Saved.');
    }

    public function delete($id) : RedirectResponse
    {
        $model = $this->quizService->deleteQuizQuestionByConditions(['id' => $id], true);

        return redirect()->route('admin.quiz.question.index', $model->quiz_id)->with('success', 'Successfully Deleted.');
    }
}
