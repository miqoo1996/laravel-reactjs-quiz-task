<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Quiz\QuizAnswerControllers;
use App\Http\Controllers\Admin\Quiz\QuizController;
use App\Http\Controllers\Admin\Quiz\QuizQuestionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

Route::group(['prefix' => 'quiz', 'as' => 'quiz.', 'controller' => QuizController::class], function (Router $router) {
    $router->get('/', 'index')->name('index');
    $router->get('/create', 'create')->name('create');
    $router->get('/update/{id}', 'update')->name('update');
    $router->post('/save/{id?}', 'save')->name('save');
    $router->get('/delete/{id}', 'delete')->name('delete');

    $router->group(['prefix' => 'question', 'as' => 'question.', 'controller' => QuizQuestionController::class], function (Router $router) {
        $router->get('/{quizId}', 'index')->name('index');
        $router->get('/create/{quizId}', 'create')->name('create');
        $router->get('/update/{id}', 'update')->name('update');
        $router->post('/save/{id?}', 'save')->name('save');
        $router->get('/delete/{id}', 'delete')->name('delete');
    });

    $router->group(['prefix' => 'answer', 'as' => 'answer.', 'controller' => QuizAnswerControllers::class], function (Router $router) {
        $router->get('/{questionId}', 'index')->name('index');
        $router->get('/create/{questionId}', 'create')->name('create');
        $router->get('/update/{id}', 'update')->name('update');
        $router->post('/save/{id?}', 'save')->name('save');
        $router->get('/delete/{id}', 'delete')->name('delete');
    });
});
