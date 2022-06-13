<?php

use App\Http\Controllers\Api\Quiz\QuizController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'quiz', 'as' => 'quiz.', 'controller' => QuizController::class], function (Router $router) {
    $router->get('/', 'index');
    $router->get('/{id}/{mode?}', 'get')->whereNumber('id');

    Route::prefix('session-token')->group(function (Router $router) {
        $router->get('/{id}/{userToken}', 'getSessionToken')->whereNumber('id')->whereAlphaNumeric('userToken');
        $router->post('/{id}/{userToken}/tokenization', 'tokenization')->whereNumber('id')->whereAlphaNumeric('userToken');
    });

    $router->get('/{id}/{userToken}/get-guest-user', 'getGuestUser')->whereNumber('id')->whereAlphaNumeric('userToken');
    $router->put('/{id}/{userToken}/add-guest-user', 'addGuestUser')->whereNumber('id')->whereAlphaNumeric('userToken');
    $router->put('/{id}/{userToken}/submit-answer', 'submitAnswer')->whereNumber('id')->whereAlphaNumeric('userToken');
});
