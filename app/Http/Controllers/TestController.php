<?php

namespace App\Http\Controllers;

use App\Test;
use App\UserTest;
use App\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;
use stdClass;

class TestController extends Controller
{
    public function get_list(Request $request)
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
        $data->tests = Test::where('hide', 0)->get();

        return json_encode($data);
    }

    public function get(Request $request)
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

        $questions = [];
        foreach ($test->questions as $question) {
            $q = new stdClass();

            $q->Number = $question->pivot->Number;
            $q->Title = $question->Title;
            $q->Question = $question->Question;
            $q->Image = $question->Image;
            $q->Time = $question->Time;
            $q->Mark = $question->pivot->Mark;
            $q->Type = $question->Type;

            $questions[] = $q;
        }

        $test->Questions = $questions;

        $data->status = "success";
        $data->test = $test;

        return json_encode($data);
    }

    public function generate_cert(Request $request)
    {
        $validator = Validator::make($request->all(), [
//            'token' => 'required|max:200',
            'user_test_id' => 'required|integer',
        ]);

        $data = new stdClass();
        $data->status = "error";

        if ($validator->fails()) {
            $data->error_type = "validate";
            $data->errors = $validator->errors();

            return json_encode($data);
        }

        $test = UserTest::find($request->input("user_test_id"));

        if (!$test) {
            $data->error_type = "exists";
            $data->message = "Test not exists";

            return json_encode($data);
        }

        $user = $test->user;

        $test->Name = $test->test->Name;
        $test->MinMarkPercent = (int)$test->test->MinMarkPercent;

        $sum = 0;
        $trueSum = 0;

        foreach ($test->userTestQuestions as $userTestQuestion) {
            $mark = $userTestQuestion->Mark;
            $sum += $mark;
            if ($userTestQuestion->IsTrue)
                $trueSum += $mark;

            $question = new stdClass();
            $question->Question = $userTestQuestion->Question;
            $question->Mark = $userTestQuestion->Mark;
            $question->IsTrue = $userTestQuestion->IsTrue;
        }
        if ($sum == 0) $sum++;
        $test->MarkPercent = round($trueSum / $sum * 100);
        $test->User = $user->user;
        $test->Mark = $trueSum;
        $test->MarkMax = $sum;

        if ($test->MarkPercent < $test->MinMarkPercent) {
            $data->error_type = "result";
            $data->message = "Не хватает баллов";

            return json_encode($data);
        }

        $pdf = new Html2Pdf('P', 'A4', 'ru', true, "UTF-8");
        $pdf->setDefaultFont('freesans');
        $pdf->pdf->SetTitle('Сертификат');
        $pdf->pdf->SetDisplayMode('fullpage');
        $pdf->writeHTML(certTemplate($test, $user));

        try {
            $pdf->output("generated_cert_{$user->Login}_{$test->ID}.pdf");
        } catch (Html2PdfException $e) {
            $data->error_type = "generate";
            $data->message = "Ошибка генерации сертификата";

            return json_encode($data);
        }
    }
}
