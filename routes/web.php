<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response('Access Denied', 403);
});

//API

// USERS
Route::post('/user/register', 'UserController@register');
Route::get('/user/register', 'UserController@register');
Route::post('/user/auth', 'UserController@auth');
Route::get('/user/auth', 'UserController@auth');

Route::post('/user/info', 'UserController@info');
Route::get('/user/info', 'UserController@info');
Route::post('/user/edit', 'UserController@edit');
Route::get('/user/edit', 'UserController@edit');

Route::post('/user/tests', 'UserController@tests');
Route::get('/user/tests', 'UserController@tests');

Route::post('/user/new_test', 'UserController@new_test');
Route::get('/user/new_test', 'UserController@new_test');

Route::post('/user/get_question', 'UserController@get_question');
Route::get('/user/get_question', 'UserController@get_question');

Route::post('/user/set_answer', 'UserController@set_answer');
Route::get('/user/set_answer', 'UserController@set_answer');

// TESTS
Route::post('/tests/get_list', 'TestController@get_list');
Route::get('/tests/get_list', 'TestController@get_list');
Route::post('/tests/get', 'TestController@get');
Route::get('/tests/get', 'TestController@get');

Route::get("/tests/generate_cert", "TestController@generate_cert");
Route::post("/tests/generate_cert", "TestController@generate_cert");

// DASHBOARD

Route::get('/dashboard/login', 'DashboardController@login');
Route::post('/dashboard/login', 'DashboardController@loginRequest');
Route::get('/dashboard/logout', 'DashboardController@logout');

Route::get('/dashboard', 'DashboardController@index');

Route::get('/dashboard/tests/add', 'DashboardController@testAdd');
Route::post('/dashboard/tests/add', 'DashboardController@testSaveAdded');
Route::get('/dashboard/tests', 'DashboardController@tests');
Route::get('/dashboard/tests/{testID}', 'DashboardController@test');
Route::post('/dashboard/tests/{testID}', 'DashboardController@testSaveEdited');

Route::get('/dashboard/tests/{testID}/questions/add', 'DashboardController@testQuestionAdd');
Route::post('/dashboard/tests/{testID}/questions/add', 'DashboardController@testQuestionSaveAdded');
Route::get('/dashboard/tests/{testID}/questions', 'DashboardController@testQuestions');
Route::get('/dashboard/tests/{testID}/questions/{questionID}', 'DashboardController@testQuestion');
Route::post('/dashboard/tests/{testID}/questions/{questionID}', 'DashboardController@testQuestionSaveEdited');
Route::get('/dashboard/tests/{testID}/questions/{questionID}/delete', 'DashboardController@testQuestionDelete');

Route::get('/dashboard/tests/{testID}/questions/{questionID}/answers/add', 'DashboardController@testQuestionAnswerAdd');
Route::post('/dashboard/tests/{testID}/questions/{questionID}/answers/add', 'DashboardController@testQuestionAnswerSaveAdded');
Route::get('/dashboard/tests/{testID}/questions/{questionID}/answers', 'DashboardController@testQuestionAnswers');
Route::get('/dashboard/tests/{testID}/questions/{questionID}/answers/true', 'DashboardController@testQuestionAnswersTrue');
Route::post('/dashboard/tests/{testID}/questions/{questionID}/answers/true', 'DashboardController@testQuestionAnswersSaveTrue');
Route::get('/dashboard/tests/{testID}/questions/{questionID}/answers/{answerID}', 'DashboardController@testQuestionAnswer');
Route::post('/dashboard/tests/{testID}/questions/{questionID}/answers/{answerID}', 'DashboardController@testQuestionAnswerSaveEdited');
Route::get('/dashboard/tests/{testID}/questions/{questionID}/answers/{answerID}/delete', 'DashboardController@testQuestionAnswerDelete');

Route::get('/dashboard/users', 'DashboardController@users');
Route::get('/dashboard/users/{userID}/tests', 'DashboardController@userTests');

Route::get('/dashboard/users/tests', 'DashboardController@usersTests');
Route::get('/dashboard/users/tests/{testID}', 'DashboardController@usersTest');
