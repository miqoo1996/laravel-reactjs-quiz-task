<?php

use App\Models\Quiz\QuizUserSession;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizGuestUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_guest_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(QuizUserSession::class, 'quiz_user_session_id')->constrained()->cascadeOnDelete();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
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
        Schema::dropIfExists('quiz_guest_users');
    }
}
