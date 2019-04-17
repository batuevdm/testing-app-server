@extends('layout.app')

@section('title')
    Вопросы теста
@endsection

@section('content')
    <h1>Вопросы теста #{{ $test->ID }}</h1>
    <a class="btn btn-success my-3" href="/dashboard/tests/{{ $test->ID }}/questions/add">Новый</a>
    @if(count($questions) > 0)
        <table class="table">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Вопрос</th>
                <th scope="col">Тип</th>
                <th scope="col">Время</th>
                <th scope="col"> </th>
            </tr>
            </thead>
            <tbody>
            @foreach($questions as $question)
                <tr>
                    <td>
                        {{ $question->pivot->Number }}
                    </td>
                    <td>
                        {{ $question->Question }}
                    </td>
                    <td>
                        {{ $question->Type }}
                    </td>
                    <td>
                        {{ $question->Time }} сек.
                    </td>
                    <td class="text-right">
                        <a class="btn btn-warning"
                           href="/dashboard/tests/{{ $test->ID }}/questions/{{ $question->ID }}">Редактировать</a>
                        <a class="btn btn-info"
                           href="/dashboard/tests/{{ $test->ID }}/questions/{{ $question->ID }}/answers">Ответы на
                            вопрос</a>
                        <a class="btn btn-danger"
                           href="/dashboard/tests/{{ $test->ID }}/questions/{{ $question->ID }}/delete"
                           onclick="return confirm('Подтвердите удаление') && confirm('Вопрос и все его ответы будут удалены!');">Удалить</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Нет вопросов</p>
    @endif
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="/dashboard/tests">Тесты</a></li>
    <li class="breadcrumb-item"><a href="/dashboard/tests/{{ $test->ID }}">{{ $test->Name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
@endsection