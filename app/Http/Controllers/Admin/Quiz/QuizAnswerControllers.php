<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\ProcessQuizAnswerRequest;
use App\Models\Quiz\QuizAnswer;
use App\Services\Quiz\QuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuizAnswerControllers extends Controller
{
    private QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function index($questionId) : View
    {
        $question = $this->quizService->fetchQuizQuestionById($questionId, true);

        $list = $this->quizService->getListQuizAnswers($questionId, true, true, ['question']);

        return view('admin.quiz.answer.index', compact('question', 'questionId', 'list'));
    }

    public function create($questionId) : View
    {
        abort_if(!$this->quizService->getQuizQuestionRepository()->applyId($questionId)->exists(), 404);

        $model = $this->quizService->getQuizAnswerRepository()->model(['quiz_question_id' => $questionId]);

        return view('admin.quiz.answer.create', compact('model'));
    }

    public function update($id) : View
    {
        $model = $this->quizService->fetchQuizAnswerById($id, true);

        return view('admin.quiz.answer.update', compact('model'));
    }

    public function save($id = null, ProcessQuizAnswerRequest $processQuizAnswerRequest) : RedirectResponse
    {
        if ($processQuizAnswerRequest->is_correct) {
            $this->quizService->getQuizAnswerRepository()->applyQuizQuestionId($processQuizAnswerRequest->quiz_question_id)->update(['is_correct' => QuizAnswer::INCORRECT_ANSWER]);
        }

        $this->quizService->getQuizAnswerRepository()->save($processQuizAnswerRequest->validated());

        return redirect()->route('admin.quiz.answer.index', $processQuizAnswerRequest->quiz_question_id)->with('success', 'Successfully Saved.');
    }

    public function delete($id) : RedirectResponse
    {
        $model = $this->quizService->deleteQuizAnswerByConditions(['id' => $id], true);

        return redirect()->route('admin.quiz.answer.index', $model->quiz_question_id)->with('success', 'Successfully Deleted.');
    }
}
