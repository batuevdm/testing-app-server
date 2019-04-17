<?php

use App\UserTest;

function generateToken(): string
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ=+-$@.,_ */|][();:><?&^%#!';
    $length = 54;
    $max = strlen($chars) - 1;
    $token = '';
    for ($i = 0; $i < $length; ++$i) {
        try {
            $token .= $chars[random_int(0, $max)];
        } catch (Exception $e) {
            $token .= $chars[rand(0, $max)];
        }
    }
    $token .= microtime();
    $token = base64_encode($token);

    return $token;
}

function checkTimeQuestions($userTestID)
{
    $userTest = UserTest::find($userTestID);
    if (!$userTest) return false;

    $userTestQuestion = $userTest->userTestQuestions
        ->where('EndTime', NULL)
        ->where('StartTime', !NULL)
        ->first();

    if (!$userTestQuestion) {
        $lastUserTestQuestion = $userTest->userTestQuestions
            ->where('EndTime', !NULL)
            ->where('StartTime', !NULL)
            ->last();

        $userTest->EndTime = $lastUserTestQuestion->EndTime;
        $userTest->save();
        return true;
    }

    $questionTime = $userTestQuestion->Time;

    if ($questionTime == 0) return true;

    $time = time();
    $userQuestionStartTime = $userTestQuestion->StartTime;

    if ($time > $userQuestionStartTime + $questionTime) {
        $userTestQuestion->EndTime = $userQuestionStartTime + $questionTime;
        $userTestQuestion->save();
        $newUserTestQuestion = $userTest->userTestQuestions
            ->where('EndTime', NULL)
            ->where('StartTime', NULL)
            ->first();
        if (!$newUserTestQuestion) return checkTimeQuestions($userTestID);

        $newUserTestQuestion->StartTime = $userQuestionStartTime + $questionTime;
        $newUserTestQuestion->save();

        return checkTimeQuestions($userTestID);
    }

    return true;
}

function getTypes()
{
    $types = [
        'QTypeRadio' => 'Radio - Один вариант из списка',
        'QTypeCheckbox' => 'Checkbox - Несколько вариантов из списка',
        'QTypeEdit' => 'Edit - Текстовое поле',
    ];

    return $types;
}

function sortByNumber($your_data)
{
    usort($your_data, function($a, $b)
    {
        return $a->Number > $b->Number;
    });

    return $your_data;
}

function certTemplate($test, $user)
{
    ob_start(); 
    ?>
    <!doctype html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <style>
            p {
                font-size: 20px;
            }
            h1 {
                font-size: 48px;
            }
        </style>
    </head>
    <body style="text-align: center">
        <h1 style="color: orangered; padding-top: 20px;">Сертификат</h1>
        <p>№ <?= $test->ID ?></p>
        <p style="margin-top: 150px;">Выдан участнику <br><u><?= $user->LastName ?> <?= $user->FirstName ?></u></p>
        <p>который прошел тест <br><u><?= $test->Name ?></u></p>
        <p>и набрал <u><?= $test->Mark ?> баллов</u> из <u><?= $test->MarkMax ?></u> возможных</p>

        <p style="text-align: left; margin-top: 220px">Дата прохождения: <?= date("d.m.Y H:i", $test->EndTime); ?></p>
        <hr>
    <p>Тест пройден в приложении Testing для Android</p>
    <p>
        <qrcode value="https://play.google.com/store/apps/details?id=ru.batuevdm.testing" ec="H" style="width: 50mm; background-color: white; color: black;"></qrcode>
    </p>
    </body>
    </html>
<?php
    return ob_get_clean();
}