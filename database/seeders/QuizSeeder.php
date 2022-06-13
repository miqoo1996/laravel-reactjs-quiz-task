<?php

namespace Database\Seeders;

use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use App\Services\Quiz\QuizService;
use Faker\Factory;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    private QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $factory = Factory::create();

        for ($num = 0; $num <= 5; $num++) {
            $quiz = $this->quizService->getQuizRepository()->query()->create(
                [
                    'title' => $factory->text(100),
                    'description' => $factory->text(250),
                    'duration' => 5,
                ],
            );

            for ($i = 0; $i <= 10; $i++) {
                $this->quizService->getQuizQuestionRepository()->query()->create(
                    [
                        'quiz_id' => $quiz->id,
                        'mode' => QuizQuestion::MODE_DEFAULT_BINARY,
                        'title' => $factory->text(100) . '?',
                    ],
                );
            }

            for ($i = 0; $i <= 10; $i++) {
                $question = $this->quizService->getQuizQuestionRepository()->query()->create(
                    [
                        'quiz_id' => $quiz->id,
                        'mode' => QuizQuestion::MODE_MULTIPLE_CHOICE,
                        'title' => $factory->text(100) . '?',
                    ],
                );

                for ($k = 0; $k <= 4; $k++) {
                    $this->quizService->getQuizAnswerRepository()->query()->create(
                        [
                            'quiz_question_id' => $question->id,
                            'is_correct' => $k === 0 ? QuizAnswer::CORRECT_ANSWER : QuizAnswer::INCORRECT_ANSWER,
                            'title' => $factory->text(100),
                        ]
                    );
                }
            }
        }
    }
}
