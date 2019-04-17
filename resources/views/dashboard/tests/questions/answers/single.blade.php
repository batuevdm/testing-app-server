@extends('layout.app')

@section('title')
    {{ $answer->Answer }}
@endsection

@section('content')
    <h1>{{ $answer->Answer }}</h1>
    <form action="" method="post">
        @csrf
        <div class="form-group">
            <label for="Answer">Ответ</label>
            <input type="text" class="form-control" name="Answer" id="Answer" value="{{ $answer->Answer }}" required>
        </div>
        <button type="submit" class="btn btn-success">Сохранить</button>
    </form>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="/dashboard/tests">Тесты</a></li>
    <li class="breadcrumb-item"><a href="/dashboard/tests/{{ $test->ID }}">{{ $test->Name }}</a></li>
    <li class="breadcrumb-item"><a href="/dashboard/tests/{{ $test->ID }}/questions">{{ $question->Question }}</a></li>
    <li class="breadcrumb-item"><a href="/dashboard/tests/{{ $test->ID }}/questions/{{ $question->ID }}/answers">Ответы
            на вопрос</a></li>
    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
@endsection