<?php

namespace App\Observers\Quiz;

use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use App\Services\Quiz\QuizService;

class QuizQuestionObserver
{
    private QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function created(QuizQuestion $quizQuestion)
    {
        if ($quizQuestion->mode === QuizQuestion::MODE_DEFAULT_BINARY) {
            $this->quizService->deleteQuizAnswerByConditions(['quiz_question_id' => $quizQuestion->id]);

            $this->quizService->getQuizAnswerRepository()->saveMultiple([
                ['quiz_question_id' => $quizQuestion->id, 'title' => 'Yes', 'is_correct' => QuizAnswer::CORRECT_ANSWER],
                ['quiz_question_id' => $quizQuestion->id, 'title' => 'No', 'is_correct' => QuizAnswer::INCORRECT_ANSWER],
            ]);
        }
    }

}
