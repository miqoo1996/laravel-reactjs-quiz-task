<?php

use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizQuestion;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizUserSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Quiz::class, 'quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')->nullable()->comment("Will be null if the user is guest")->constrained()->cascadeOnDelete();
            $table->enum('mode', QuizQuestion::MODES)->default(QuizQuestion::MODE_DEFAULT_BINARY);
            $table->string('user_token');
            $table->string('token');
            $table->timestampTz('expires_at');
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
        Schema::dropIfExists('quiz_user_sessions');
    }
}
