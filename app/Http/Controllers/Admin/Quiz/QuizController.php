<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\ProcessQuizRequest;
use App\Services\Quiz\QuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuizController extends Controller
{
    private QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function index() : View
    {
        $list = $this->quizService->getListQuizzes(true, true);

        return view('admin.quiz.index', compact('list'));
    }

    public function create() : View
    {
        $model = $this->quizService->getQuizRepository()->model();

        return view('admin.quiz.create', compact('model'));
    }

    public function update($id) : View
    {
        $model = $this->quizService->fetchQuizById($id, true);

        return view('admin.quiz.update', compact('model'));
    }

    public function save($id = null, ProcessQuizRequest $processQuizRequest) : RedirectResponse
    {
        $this->quizService->fetchQuizById($id, false, true);

        $this->quizService->getQuizRepository()->save($processQuizRequest->validated());

        return redirect()->route('admin.quiz.index')->with('success', 'Successfully Saved.');
    }

    public function delete($id) : RedirectResponse
    {
        $this->quizService->deleteQuizByConditions(['id' => $id]);

        return redirect()->route('admin.quiz.index')->with('success', 'Successfully Deleted.');
    }
}
