<?php

namespace App\Http\Controllers;

use App\Test;
use App\User;
use App\UserTest;
use App\UserTestQuestion;
use App\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|min:3|max:30|unique:users,login',
            'password' => 'required|min:6|max:255',
            'first_name' => 'required|min:1|max:50',
            'last_name' => 'required|min:1|max:50',
        ], [
            'login.unique' => 'Данный логин уже занят'
        ]);

        $data = new stdClass();
        $data->status = "error";

        if ($validator->fails()) {
            $data->error_type = "validate";
            $data->errors = $validator->errors();

            return json_encode($data);
        }

        $user = new User();
        $user->Login = $request->input('login');
        $user->Password = password_hash($request->input('password'), PASSWORD_DEFAULT);
        $user->FirstName = $request->input('first_name');
        $user->LastName = $request->input('last_name');

        if (!$user->save()) {
            $data->error_type = "save";
            $data->message = "Error save model";

            return json_encode($data);
        }

        $data->status = "success";
        $data->result = "true";

        return json_encode($data);
    }

    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|min:3|max:30',
            'password' => 'required|min:6|max:255',
            'device' => 'min:1|max:255',
        ]);

        $data = new stdClass();
        $data->status = "error";

        if ($validator->fails()) {
            $data->error_type = "validate";
            $data->errors = $validator->errors();

            return json_encode($data);
        }

        $login = $request->input('login');
        $password = $request->input('password');
        $user = User::where('login', '=', $login)->first();

        if (!$user) {
            $data->error_type = "auth";
            $data->message = "Неверный логин или пароль";

            return json_encode($data);
        }
        if (!password_verify($password, $user->Password)) {
            $data->error_type = "auth";
            $data->message = "Неверный логин или пароль";

            return json_encode($data);
        }

        $token = new UserToken();
        $token->UserID = $user->ID;
        $token->Token = generateToken();
        $token->IP = $request->getClientIp();
        $token->Device = $request->input('device', null);

        if (!$token->save()) {
            $data->error_type = "save";
            $data->message = "Error save model";

            return json_encode($data);
        }

        $data->status = "success";
        $data->token = $token->Token;

        return json_encode($data);
    }

    public function info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|max:200',
        ]);

        $data = new stdClass();
        $data->status = "error";

        if ($validator->fails()) {
            $data->error_type = "validate";
            $data->errors = $validator->errors();

            return json_encode($data);
        }

        $token = $request->input('token');
        $user = UserToken::where('Token', $token)
            ->first();

        if (!$user) {
            $data->error_type = "token";
            $data->message = "Bad token";

            return json_encode($data);
        }

        $data->status = "success";
        $data->user = $user->user;

        return json_encode($data);
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|max:200',
            'login' => 'required|min:3|max:30',
            'password' => 'min:6|max:255',
            'old_password' => 'required_with:password|min:6|max:255',
            'first_name' => 'required|min:1|max:50',
            'last_name' => 'required|min:1|max:50',
        ], [
            'login.unique' => 'Данный логин уже занят'
        ]);

        $data = new stdClass();
        $data->status = "error";

        if ($validator->fails()) {
            $data->error_type = "validate";
            $data->errors = $validator->errors();

            return json_encode($data);
        }

        $token = $request->input('token');
        $user = UserToken::where('Token', $token)
            ->first();

        if (!$user) {
            $data->error_type = "token";
            $data->message = "Bad token";

            return json_encode($data);
        }

        $user = $user->user;

        $login = $request->input('login');
        if ($login != $user->Login) {
            $oldUser = User::where('Login', '=', $login)->first();
            if ($oldUser) {
                $data->error_type = "login";
                $data->message = 'Данный логин уже занят';

                return json_encode($data);
            }
        }

        $editedUser = User::find($user->ID);
        $editedUser->Login = $login;
        if ($request->has('password')) {
            $old_password = $request->input('old_password');
            $new_password = $request->input('password');

            if (!password_verify($old_password, $user->Password)) {
                $data->error_type = "old_password";
                $data->message = "Неверный пароль";

                return json_encode($data);
            }

            $editedUser->Password = password_hash($new_password, PASSWORD_DEFAULT);
            UserToken::query()
                ->where("token", "<>", $token)
                ->delete();
        }
        $editedUser->FirstName = $request->input('first_name');
        $editedUser->LastName = $request->input('last_name');

        if (!$editedUser->save()) {
            $data->error_type = "save";
            $data->message = "Error save model";

            return json_encode($data);
        }

        $data->status = "success";
        $data->result = "true";

        return json_encode($data);
    }

    public function tests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|max:200',
        ]);

        $data = new stdClass();
        $data->status = "error";

        if ($validator->fails()) {
            $data->error_type = "validate";
            $data->errors = $validator->errors();

            return json_encode($data);
        }

        $token = $request->input('token');
        $user = UserToken::where('Token', $token)
            ->first();

        if (!$user) {
            $data->error_type = "token";
            $data->message = "Bad token";

            return json_encode($data);
        }
        foreach ($user->user->tests as $test) checkTimeQuestions($test->ID);

        $user = UserToken::where('Token', $token)
            ->first();
        $tests = [];
        foreach ($user->user->tests as $test) {
            $test->Name = $test->test->Name;
            $test->MinMarkPercent = (int)$test->test->MinMarkPercent;

            $sum = 0;
            $trueSum = 0;
            $questions = [];
            foreach ($test->userTestQuestions as $userTestQuestion) {
                $mark = $userTestQuestion->Mark;
                $sum += $mark;
                if ($userTestQuestion->IsTrue)
                    $trueSum += $mark;

                $question = new stdClass();
                $question->Question = $userTestQuestion->Question;
                $question->Mark = $userTestQuestion->Mark;
                $question->IsTrue = $userTestQuestion->IsTrue;
                $questions[] = $question;
            }
            $test->MarkPercent = round($trueSum / $sum * 100);
            $test->UserTestQuestions = $questions;

            $tests[] = $test;
        }

        $data->status = "success";
        $data->tests = $tests;

        return json_encode($data);
    }

    public function new_test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|max:200',
            'test_id' => 'required|integer',
        ]);

        $data = new stdClass();
        $data->status = "error";

        if ($validator->fails()) {
            $data->error_type = "validate";
            $data->errors = $validator->errors();

            return json_encode($data);
        }

        $token = $request->input('token');
        $user = UserToken::where('Token', $token)
            ->first();

        if (!$user) {
            $data->error_type = "token";
            $data->message = "Bad token";

            return json_encode($data);
        }

        $test = Test::where('hide', 0)
            ->find($request->input('test_id'));
        if (!$test) {
            $data->error_type = "exists";
            $data->message = "Test not exists";

            return json_encode($data);
        }

        $old_tests = UserTest::query()
            ->where("TestID", $test->ID)
            ->whereNull("EndTime")
            ->count();

        if ($old_tests > 0) {
            $data->error_type = "old";
            $data->message = "Сначала необходимо завершить предыдущий тест";

            return json_encode($data);
        }

        DB::beginTransaction();
        $userTest = new UserTest();
        $userTest->UserID = $user->user->ID;
        $userTest->TestID = $test->ID;
        $userTest->StartTime = time();

        if (!$userTest->save()) {
            $data->error_type = "save";
            $data->message = "Error save model";
            DB::rollBack();

            return json_encode($data);
        }

        $testQuestions = $test->questions;
        if (count($testQuestions) < 1) {
            $data->error_type = "questions";
            $data->message = "Test is empty";
            DB::rollBack();

            return json_encode($data);
        }

        $userTestFirstQuestion = new UserTestQuestion();
        $userTestFirstQuestion->UserTestID = $userTest->ID;
        $userTestFirstQuestion->QuestionID = $testQuestions[0]->ID;
        $userTestFirstQuestion->Question = $testQuestions[0]->Question;
        $userTestFirstQuestion->IsTrue = 0;
        $userTestFirstQuestion->Mark = $testQuestions[0]->pivot->Mark;
        $userTestFirstQuestion->StartTime = time();
        $userTestFirstQuestion->Time = $testQuestions[0]->Time;

        if (!$userTestFirstQuestion->save()) {
            $data->error_type = "save";
            $data->message = "Error save model";
            DB::rollBack();

            return json_encode($data);
        }

        if (count($testQuestions) > 1) {
            unset($testQuestions[0]);

            foreach ($testQuestions as $testQuestion) {
                $userTestQuestion = new UserTestQuestion();
                $userTestQuestion->UserTestID = $userTest->ID;
                $userTestQuestion->QuestionID = $testQuestion->ID;
                $userTestQuestion->Question = $testQuestion->Question;
                $userTestQuestion->IsTrue = 0;
                $userTestQuestion->Mark = $testQuestion->pivot->Mark;
                $userTestQuestion->Time = $testQuestion->Time;

                if (!$userTestQuestion->save()) {
                    $data->error_type = "save";
                    $data->message = "Error save model";
                    DB::rollBack();

                    return json_encode($data);
                }
            }
        }

        $data->status = "success";
        $data->UserTestID = $userTest->ID;
        DB::commit();

        return json_encode($data);
    }

    public function get_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|max:200',
            'user_test_id' => 'required|integer',
        ]);

        $data = new stdClass();
        $data->status = "error";

        if ($validator->fails()) {
            $data->error_type = "validate";
            $data->errors = $validator->errors();

            return json_encode($data);
        }

        $token = $request->input('token');
        $user = UserToken::where('Token', $token)
            ->first();

        if (!$user) {
            $data->error_type = "token";
            $data->message = "Bad token";

            return json_encode($data);
        }

        $userTest = UserTest::where("UserID", $user->user->ID)
            ->find($request->input('user_test_id'));
        if (!$userTest) {
            $data->error_type = "exists";
            $data->message = "User test not exists";

            return json_encode($data);
        }

        checkTimeQuestions($userTest->ID);

        $userTest = UserTest::where("UserID", $user->user->ID)
            ->find($request->input('user_test_id'));

        $testQuestion = $userTest->userTestQuestions
            ->where('StartTime', !NULL)
            ->where('EndTime', NULL)
            ->first();

        if (!$testQuestion) {
            $data->error_type = "end";
            $data->message = "Конец теста";

            return json_encode($data);
        }
        $question = new stdClass();
        $question->ID = $testQuestion->ID;
        $question->UserTestID = $testQuestion->UserTestID;

        $question->Title = $testQuestion->question->Title;
        $question->Question = $testQuestion->Question;
        $question->Image = $testQuestion->question->Image;
        $question->Type = $testQuestion->question->Type;
        $question->StartTime = $testQuestion->StartTime;
        if ($testQuestion->question->Time > 0) {
            $question->Time = $testQuestion->StartTime + $testQuestion->Time - time();
            $question->TimeType = "Timer";
        } else {
            $question->Time = 0;
            $question->TimeType = "Infinity";
        }

        $questionType = "App\\" . $testQuestion->question->Type . "Answer";
        $questionAnswers = $questionType::where("QuestionID", $testQuestion->QuestionID)->get();

        if ($question->Type != "QTypeEdit") {
            $answers = [];

            foreach ($questionAnswers as $questionAnswer) {
                $answers[] = $questionAnswer->answer;
            }
            shuffle($answers);
            $question->Answers = $answers;
        }

        $data->status = "success";
        $data->Question = $question;

        return json_encode($data);
    }

    public function set_answer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|max:200',
            'user_test_id' => 'required|integer',
            'user_question_id' => 'required|integer',
            'answer' => 'required|string',
        ]);

        $data = new stdClass();
        $data->status = "error";

        if ($validator->fails()) {
            $data->error_type = "validate";
            $data->errors = $validator->errors();

            return json_encode($data);
        }

        $token = $request->input('token');
        $user = UserToken::where('Token', $token)
            ->first();

        if (!$user) {
            $data->error_type = "token";
            $data->message = "Bad token";

            return json_encode($data);
        }

        $userTest = UserTest::where("UserID", $user->user->ID)
            ->find($request->input('user_test_id'));
        if (!$userTest) {
            $data->error_type = "exists";
            $data->message = "User test not exists";

            return json_encode($data);
        }

        checkTimeQuestions($userTest->ID);

        $userTest = UserTest::where("UserID", $user->user->ID)
            ->find($request->input('user_test_id'));

        $testQuestion = $userTest->userTestQuestions
            ->where('StartTime', !NULL)
            ->where('EndTime', NULL)
            ->first();

        if (!$testQuestion) {
            $data->error_type = "end";
            $data->message = "Конец теста";

            return json_encode($data);
        }

        $userTestQuestion = UserTestQuestion::where("UserTestID", $userTest->ID)
            ->where("StartTime", '>', 0)
            ->where("EndTime", NULL)
            ->find($request->input('user_question_id'));
        if (!$userTestQuestion) {
            $data->error_type = "exists";
            $data->message = "User question not exists";

            return json_encode($data);
        }

        $questionType = "App\\" . $userTestQuestion->question->Type . "Answer";
        $answers = $questionType::where("QuestionID", $userTestQuestion->QuestionID)->get();

        $isTrue = false;
        switch ($userTestQuestion->question->Type) {
            case 'QTypeEdit':
                // Format: Word
                $user_answer = trim($request->input('answer'));

                foreach ($answers as $answer) {
                    $this_answer = $answer->answer;
                    $true_answer = $this_answer->Answer;
                    if (mb_strtolower($user_answer, "utf-8") == mb_strtolower($true_answer, "utf-8")) {
                        $isTrue = true;
                        break;
                    }
                }

                break;

            case 'QTypeRadio':
                // Format: Number
                $user_answer = (int)$request->input('answer');

                foreach ($answers as $answer) {
                    if ($answer->AnswerID == $user_answer) {
                        if ($answer->IsTrue)
                            $isTrue = true;
                        break;
                    }
                }

                break;

            case 'QTypeCheckbox':
                // Format: Number1:Number2:Number3...
                $user_answer = $request->input('answer');
                $user_answer = explode(":", $user_answer);
                $user_answer = array_unique($user_answer);

                $localIsTrue = true;
                if ($user_answer && count($user_answer) > 0) {
                    foreach ($answers as $answer) {
                        if (in_array($answer->AnswerID, $user_answer)) {
                            if (!$answer->IsTrue)
                                $localIsTrue = false;
                        } else {
                            if ($answer->IsTrue)
                                $localIsTrue = false;
                        }
                        if (!$localIsTrue) break;
                    }
                }

                $isTrue = $localIsTrue;

                break;
        }

        if ($isTrue) {
            $userTestQuestion->IsTrue = 1;
        }
        $userTestQuestion->EndTime = time();
        if (!$userTestQuestion->save()) {
            $data->error_type = "save";
            $data->message = "Error save model";

            return json_encode($data);
        }

        $next_answer = UserTestQuestion::find($userTestQuestion->ID + 1);
        if ($next_answer) {
            $next_answer->StartTime = time();
            if (!$next_answer->save()) {
                $data->error_type = "save";
                $data->message = "Error save model";

                return json_encode($data);
            }
        }

        $data->status = "success";

        return json_encode($data);
    }
}
