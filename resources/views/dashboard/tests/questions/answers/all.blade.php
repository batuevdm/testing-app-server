@extends('layout.app')

@section('title')
    Ответы на вопрос
@endsection

@section('content')
    <h1>Ответы на вопрос №{{ $question->pivot->Number }} теста #{{ $test->ID }}</h1>
    <a class="btn btn-success my-3" href="/dashboard/tests/{{ $test->ID }}/questions/{{ $question->ID }}/answers/add">Новый</a>
    @if(count($answers) > 0)
        @if(!isset($answers[0]->Number) && isset($answers[0]->IsTrue))
            <a class="btn btn-primary my-3" href="/dashboard/tests/{{ $test->ID }}/questions/{{ $question->ID }}/answers/true">Отметить верные</a>
        @endif
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Ответ</th>
                <th scope="col"> </th>
            </tr>
            </thead>
            <tbody>
            @foreach($answers as $answer)
                <tr>
                    <td>
                        {{ $answer->Answer }} {{ isset($answer->IsTrue) && $answer->IsTrue ? '(верный)' : '' }}
                    </td>
                    <td class="text-right">
                        <a class="btn btn-warning"
                           href="/dashboard/tests/{{ $test->ID }}/questions/{{ $question->ID }}/answers/{{ $answer->ID }}">Редактировать</a>
                        <a class="btn btn-danger"
                           href="/dashboard/tests/{{ $test->ID }}/questions/{{ $question->ID }}/answers/{{ $answer->ID }}/delete"
                           onclick="return confirm('Подтвердите удаление');">Удалить</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Нет ответов</p>
    @endif
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="/dashboard/tests">Тесты</a></li>
    <li class="breadcrumb-item"><a href="/dashboard/tests/{{ $test->ID }}">{{ $test->Name }}</a></li>
    <li class="breadcrumb-item"><a href="/dashboard/tests/{{ $test->ID }}/questions">{{ $question->Question }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
@endsection