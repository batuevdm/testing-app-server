@extends('layout.app')

@section('title')
    Результат теста
@endsection

@section('content')
    <h1>@yield('title') #{{ $test->ID }}</h1>
    <p>
        Пользователь: {{ $test->User }}
    </p>
    <p>
        Тест: {{ $test->Test }}
    </p>
    <p>
        Дата начала: {{ $test->StartDate }}
    </p>
    @if($test->Status == 'Заверщен')
        <p>
            Дата завершения: {{ $test->EndDate }}
        </p>
    @endif
    <p>
        Статус: {{ $test->Status }}
    </p>
    <p>
        Результат: {{ $test->RightAnswers }}
    </p>
    <h2>Вопросы</h2>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Вопрос</th>
            <th scope="col">Результат</th>
            <th scope="col">Дата начала</th>
            <th scope="col">Дата завершения</th>
        </tr>
        </thead>
        <tbody>
        @foreach($test->Questions as $question)
            <tr>
                <td>
                    {{ $question->Question }}
                </td>
                <td>
                    @if($question->IsTrue)
                        Верно {{ $question->Mark }} балла(ов)
                    @else
                        Неверно (0 баллов)
                    @endif
                </td>
                <td>
                    {{ $question->StartTime ? date("d.m.Y H:i:s", $question->StartTime) : 'Не начался' }}
                </td>
                <td>
                    {{ $question->EndTime ? date("d.m.Y H:i:s", $question->EndTime) : 'Не завершен' }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="/dashboard/users/tests">Результаты тестов</a></li>
    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
@endsection