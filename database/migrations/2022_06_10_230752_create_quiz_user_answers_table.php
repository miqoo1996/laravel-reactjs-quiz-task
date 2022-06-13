<?php

use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizUserSession;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizUserAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(QuizUserSession::class, 'quiz_user_session_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(QuizQuestion::class, 'quiz_question_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(QuizAnswer::class, 'quiz_answer_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_user_answers');
    }
}
