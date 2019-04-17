@extends('layout.app')

@section('title')
    Тесты
@endsection

@section('content')
    <h1>Тесты</h1>
    <a class="btn btn-success my-3" href="/dashboard/tests/add">Новый</a>
    @if(count($tests) > 0)
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Название</th>
                <th scope="col"> </th>
            </tr>
            </thead>
            <tbody>
            @foreach($tests as $test)
                <tr>
                    <td>
                        {{ $test->ID }}
                    </td>
                    <td>
                        {{ $test->Name }}
                    </td>
                    <td class="text-right">
                        <a class="btn btn-warning" href="/dashboard/tests/{{ $test->ID }}">Редактировать</a>
                        <a class="btn btn-info" href="/dashboard/tests/{{ $test->ID }}/questions">Вопросы теста</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Нет тестов</p>
    @endif
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
@endsection