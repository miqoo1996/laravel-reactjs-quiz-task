<?php

use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizGuestUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor( Quiz::class, 'quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor( QuizGuestUser::class, 'quiz_guest_user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_ended');
            $table->smallInteger('unanswered_count')->default(0);
            $table->smallInteger('score');
            $table->integer('duration_left');
            $table->timestampTz('submit_date');
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
        Schema::dropIfExists('quiz_statistics');
    }
}
