<?php

namespace App\Http\Controllers;

use App\AnswerTypeEasy;
use App\Question;
use App\Test;
use App\User;
use App\UserTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use stdClass;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('CSRF');
        $this->middleware('auth')
            ->except('login', 'loginRequest');
        $this->middleware('guest')
            ->only('login', 'loginRequest');
    }

    public function login()
    {
        return view('dashboard.login');
    }

    public function loginRequest(Request $request)
    {
        $this->validate($request, [
            'login' => 'required|min:3|max:30',
            'password' => 'required|min:6|max:255',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');
        $user = User::where('Login', $login)->first();

        if ($user) {
            if (password_verify($password, $user->Password)) {
                if ($user->Role == 'admin') {
                    Session::put('user_id', $user->ID);
                    return redirect('/dashboard');
                }
                return back()
                    ->with('error', 'Доступ запрещен')
                    ->withInput();
            }

            return back()
                ->with('error', 'Неверный логин или пароль')
                ->withInput();
        }

        return back()
            ->withErrors($user)
            ->withInput();
    }

    public function logout()
    {
        if (Session::has('user_id'))
            Session::remove('user_id');

        return redirect('/dashboard/login');
    }

    public function index()
    {
        return view('dashboard.index');
    }

    public function tests()
    {
        $tests = Test::orderBy('ID', 'desc')->get();
        return view('dashboard.tests.all', [
            'tests' => $tests
        ]);
    }

    public function testAdd()
    {
        return view('dashboard.tests.add');
    }

    public function testSaveAdded(Request $request)
    {
        $this->validate($request, [
            'Name' => 'required|string|max:150',
            'MinMarkPercent' => 'required|integer|min:0|max:100',
            'hide' => 'required|boolean',
        ]);
        $test = new Test();

        $test->Name = $request->input('Name');
        $test->MinMarkPercent = $request->input('MinMarkPercent');
        $test->hide = $request->input('hide');

        if ($test->save()) {
            return redirect('/dashboard/tests')
                ->with('status', 'Сохранено');
        }

        return back()
            ->withErrors($test);
    }

    public function test($testID)
    {
        $test = Test::findOrFail($testID);
        return view('dashboard.tests.single', [
            'test' => $test
        ]);
    }

    public function testSaveEdited(Request $request, $testID)
    {
        $this->validate($request, [
            'Name' => 'required|string|max:150',
            'MinMarkPercent' => 'required|integer|min:0|max:100',
            'hide' => 'required|boolean',
        ]);
        $test = Test::findOrFail($testID);

        $test->Name = $request->input('Name');
        $test->MinMarkPercent = $request->input('MinMarkPercent');
        $test->hide = $request->input('hide');

        if ($test->save()) {
            return back()
                ->with('status', 'Сохранено');
        }

        return back()
            ->withErrors($test);
    }

    public function testQuestions($testID)
    {
        $test = Test::findOrFail($testID);
        $questions = $test->questions;
        return view('dashboard.tests.questions.all', [
            'test' => $test,
            'questions' => $questions,
        ]);
    }

    public function testQuestion($testID, $questionID)
    {
        $test = Test::findOrFail($testID);
        $question = $test->questions()->findOrFail($questionID);

        return view('dashboard.tests.questions.single', [
            'test' => $test,
            'question' => $question,
        ]);
    }

    public function testQuestionAdd($testID)
    {
        $test = Test::findOrFail($testID);
        return view('dashboard.tests.questions.add', [
            'test' => $test
        ]);
    }

    public function testQuestionSaveAdded(Request $request, $testID)
    {
        $this->validate($request, [
            'Title' => 'max:150',
            'Question' => 'required|string|max:500',
            'Type' => 'required|in:QTypeEdit,QTypeRadio,QTypeCheckbox',
            'Time' => 'required|integer|min:0',
            'Number' => 'required|integer|min:1',
            'Mark' => 'required|integer|min:0'
        ]);
        $test = Test::findOrFail($testID);
        $question = new Question();

        $question->Title = $request->input('Title');
        $question->Question = $request->input('Question');
        $question->Type = $request->input('Type');
        $question->Time = $request->input('Time');

        $result = $test->questions()->save($question, [
            'Number' => $request->input('Number'),
            'Mark' => $request->input('Mark'),
        ]);

        if ($result) {
            return redirect('/dashboard/tests/' . $testID . '/questions')
                ->with('status', 'Сохранено');
        }

        return back()
            ->withErrors($question);
    }

    public function testQuestionSaveEdited(Request $request, $testID, $questionID)
    {
        $this->validate($request, [
            'Title' => 'string|max:150',
            'Question' => 'required|string|max:500',
            'Type' => 'required|in:QTypeEdit,QTypeRadio,QTypeCheckbox',
            'Time' => 'required|integer|min:0',
            'Number' => 'required|integer|min:1',
            'Mark' => 'required|integer|min:0'
        ]);
        $test = Test::findOrFail($testID);
        $question = $test->questions()->findOrFail($questionID);

        $question->Title = $request->input('Title');
        $question->Question = $request->input('Question');
        $question->Type = $request->input('Type');
        $question->Time = $request->input('Time');
        $test->questions()->detach($questionID);
        $test->questions()->attach($questionID, [
            'Number' => $request->input('Number'),
            'Mark' => $request->input('Mark'),
        ]);

        if ($question->save()) {
            return back()
                ->with('status', 'Сохранено');
        }

        return back()
            ->withErrors($question);
    }

    public function testQuestionDelete($testID, $questionID)
    {
        $test = Test::findOrFail($testID);
        $question = $test->questions()->findOrFail($questionID);

        if ($question->delete()) {
            return back()
                ->with('status', 'Удалено');
        }

        return back()->with('error', 'Ошибка удаления');
    }

    public function testQuestionAnswers($testID, $questionID)
    {
        $test = Test::findOrFail($testID);
        $question = $test->questions()->findOrFail($questionID);
        $questionType = "App\\" . $question->Type . "Answer";
        $questionAnswers = $questionType::where("QuestionID", $questionID)->get();
        $answers = [];
        foreach ($questionAnswers as $questionAnswer) {
            $a = new stdClass();
            $a->ID = $questionAnswer->AnswerID;
            $a->Answer = $questionAnswer->answer->Answer;
            $a->Image = $questionAnswer->answer->Image;
            if (isset($questionAnswer->IsTrue)) $a->IsTrue = $questionAnswer->IsTrue;
            $answers[] = $a;
        }
        return view('dashboard.tests.questions.answers.all', [
            'test' => $test,
            'question' => $question,
            'answers' => $answers
        ]);
    }

    public function testQuestionAnswer($testID, $questionID, $answerID)
    {
        $test = Test::findOrFail($testID);
        $question = $test->questions()->findOrFail($questionID);
        $questionType = "App\\" . $question->Type . "Answer";
        $questionAnswer = $questionType::where("QuestionID", $questionID)->where("AnswerID", $answerID)->first();

        $a = new stdClass();
        $a->ID = $questionAnswer->AnswerID;
        $a->Answer = $questionAnswer->answer->Answer;
        $a->Image = $questionAnswer->answer->Image;
        if (isset($questionAnswer->IsTrue)) $a->IsTrue = $questionAnswer->IsTrue;

        return view('dashboard.tests.questions.answers.single', [
            'test' => $test,
            'question' => $question,
            'answer' => $a
        ]);
    }

    public function testQuestionAnswerAdd($testID, $questionID)
    {
        $test = Test::findOrFail($testID);
        $question = $test->questions()->findOrFail($questionID);
        return view('dashboard.tests.questions.answers.add', [
            'test' => $test,
            'question' => $question
        ]);
    }

    public function testQuestionAnswerSaveAdded(Request $request, $testID, $questionID)
    {
        $this->validate($request, [
            'Answer' => 'required|string|max:200'
        ]);
        $test = Test::findOrFail($testID);
        $question = $test->questions()->findOrFail($questionID);

        $questionType = "App\\" . $question->Type . "Answer";

        $answer = new AnswerTypeEasy();
        $answer->Answer = $request->input('Answer');

        DB::beginTransaction();
        if ($answer->save()) {
            $questionAnswer = new $questionType();
            $questionAnswer->QuestionID = $questionID;
            $questionAnswer->AnswerID = $answer->ID;

            if ($questionAnswer->save()) {
                DB::commit();
                return redirect('/dashboard/tests/' . $testID . '/questions/' . $questionID . '/answers')
                    ->with('status', 'Сохранено');
            }
        }

        DB::rollBack();

        return back()
            ->withErrors($answer);
    }

    public function testQuestionAnswerSaveEdited(Request $request, $testID, $questionID, $answerID)
    {
        $this->validate($request, [
            'Answer' => 'required|string|max:200'
        ]);

        $answer = AnswerTypeEasy::findOrFail($answerID);
        $answer->Answer = $request->input('Answer');

        if ($answer->save()) {
            return back()
                ->with('status', 'Сохранено');
        }

        return back()
            ->withErrors($answer);
    }

    public function testQuestionAnswerDelete($testID, $questionID, $answerID)
    {
        $answer = AnswerTypeEasy::findOrFail($answerID);

        if ($answer->delete()) {
            return back()
                ->with('status', 'Удалено');
        }

        return back()->with('error', 'Ошибка удаления');
    }

    public function testQuestionAnswersTrue($testID, $questionID)
    {
        $test = Test::findOrFail($testID);
        $question = $test->questions()->findOrFail($questionID);

        if ($question->Type == 'QTypeEdit')
            return back();

        $questionType = "App\\" . $question->Type . "Answer";
        $questionAnswers = $questionType::where("QuestionID", $questionID)->get();

        return view('dashboard.tests.questions.answers.true', [
            'test' => $test,
            'question' => $question,
            'answers' => $questionAnswers
        ]);
    }

    public function testQuestionAnswersSaveTrue(Request $request, $testID, $questionID)
    {
        $this->validate($request, [
            'True' => 'required|array'
        ]);
        $test = Test::findOrFail($testID);
        $question = $test->questions()->findOrFail($questionID);

        $questionType = "App\\" . $question->Type . "Answer";
        $questionAnswers = $questionType::where("QuestionID", $questionID)->get();

        if ($question->Type == 'QTypeRadio') {
            $true = (int)$request->input('True')[0];
            foreach ($questionAnswers as $questionAnswer) {
                if ($questionAnswer->AnswerID == $true) {
                    $questionAnswer->IsTrue = 1;
                } else {
                    $questionAnswer->IsTrue = 0;
                }
                if (!$questionAnswer->save()) {
                    return back()
                        ->with('error', 'Ошибка');
                }
            }
            return back()
                ->with('status', 'Сохранено');

        } elseif ($question->Type == 'QTypeCheckbox') {
            $true = $request->input('True');
            foreach ($questionAnswers as $questionAnswer) {
                foreach ($true as $item) {
                    if ($questionAnswer->AnswerID == $item) {
                        $questionAnswer->IsTrue = 1;
                        break;
                    } else {
                        $questionAnswer->IsTrue = 0;
                    }
                }
                if (!$questionAnswer->save()) {
                    return back()
                        ->with('error', 'Ошибка');
                }
            }
            return back()
                ->with('status', 'Сохранено');
        }

        return back()
            ->withErrors($question);
    }

    public function users()
    {
        $users = User::all();
        return view('dashboard.users.all', [
            'users' => $users
        ]);
    }

    public function usersTests()
    {
        $tests = UserTest::all();
        foreach ($tests as $test_item) {
            checkTimeQuestions($test_item->ID);
        }
        $tests = UserTest::all();
        $allTests = [];
        foreach ($tests as $test_item) {
            $testItem = new stdClass();
            $test = Test::findOrFail($test_item->TestID);
            $user = User::findOrFail($test_item->UserID);

            $testItem->ID = $test_item->ID;
            $testItem->User = $user->FirstName . ' ' . $user->LastName;
            $testItem->UserID = $user->ID;
            $testItem->Test = $test->Name;
            $testItem->StartDate = date("d.m.Y H:i:s", $test_item->StartTime);
            $testItem->Status = $test_item->EndTime ? 'Завершен' : 'Не завершен';

            $sum = 0;
            $trueSum = 0;
            $items = 0;
            $trueItems = 0;
            foreach ($test_item->userTestQuestions as $userTestQuestion) {
                $mark = $userTestQuestion->Mark;
                $sum += $mark;
                $items++;
                if ($userTestQuestion->IsTrue) {
                    $trueSum += $mark;
                    $trueItems++;
                }
            }
            $testItem->RightAnswers = $trueItems . ' из ' . $items . ' (' . round($trueSum / $sum * 100) . '%)';

            $allTests[] = $testItem;
        }

        return view('dashboard.users.tests.tests', [
            'tests' => $allTests
        ]);
    }

    public function userTests($userID)
    {
        $user = User::findOrFail($userID);
        $tests = $user->tests;

        foreach ($tests as $test_item) {
            checkTimeQuestions($test_item->ID);
        }

        $user = User::findOrFail($userID);
        $tests = $user->tests;

        $allTests = [];
        foreach ($tests as $test_item) {
            $testItem = new stdClass();
            $test = Test::findOrFail($test_item->TestID);

            $testItem->ID = $test_item->ID;
            $testItem->User = $user->FirstName . ' ' . $user->LastName;
            $testItem->UserID = $user->ID;
            $testItem->Test = $test->Name;
            $testItem->StartDate = date("d.m.Y H:i:s", $test_item->StartTime);
            $testItem->Status = $test_item->EndTime ? 'Завершен' : 'Не завершен';

            $sum = 0;
            $trueSum = 0;
            $items = 0;
            $trueItems = 0;
            foreach ($test_item->userTestQuestions as $userTestQuestion) {
                $mark = $userTestQuestion->Mark;
                $sum += $mark;
                $items++;
                if ($userTestQuestion->IsTrue) {
                    $trueSum += $mark;
                    $trueItems++;
                }
            }
            $testItem->RightAnswers = $trueItems . ' из ' . $items . ' (' . round($trueSum / $sum * 100) . '%)';

            $allTests[] = $testItem;
        }

        return view('dashboard.users.tests.user', [
            'tests' => $allTests,
            'user' => $user
        ]);
    }

    public function usersTest($testID)
    {
        $userTest = UserTest::findOrFail($testID);
        checkTimeQuestions($testID);

        $testItem = new stdClass();
        $test = Test::findOrFail($userTest->TestID);
        $user = User::findOrFail($userTest->UserID);

        $testItem->ID = $userTest->ID;
        $testItem->User = $user->FirstName . ' ' . $user->LastName;
        $testItem->UserID = $user->ID;
        $testItem->Test = $test->Name;
        $testItem->StartDate = date("d.m.Y H:i:s", $userTest->StartTime);
        $testItem->EndDate = date("d.m.Y H:i:s", $userTest->EndTime);
        $testItem->Status = $userTest->EndTime ? 'Завершен' : 'Не завершен';

        $sum = 0;
        $trueSum = 0;
        $items = 0;
        $trueItems = 0;
        foreach ($userTest->userTestQuestions as $userTestQuestion) {
            $mark = $userTestQuestion->Mark;
            $sum += $mark;
            $items++;
            if ($userTestQuestion->IsTrue) {
                $trueSum += $mark;
                $trueItems++;
            }
        }
        $testItem->Questions = $userTest->userTestQuestions;
        $testItem->RightAnswers = $trueItems . ' из ' . $items . ' (' . round($trueSum / $sum * 100) . '%)';


        return view('dashboard.users.tests.single', [
            'test' => $testItem,
            'user' => $user
        ]);
    }
}
