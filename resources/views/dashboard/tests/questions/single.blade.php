@extends('layout.app')

@section('title')
    {{ $question->Question }} - {{ $test->Name }}
@endsection

@section('content')
    <h1>{{ $question->Question }} - {{ $test->Name }}</h1>
    <form action="" method="post">
        @csrf
        <div class="form-group">
            <label for="Title">Подсказка</label>
            <input type="text" class="form-control" name="Title" id="Title" value="{{ $question->Title }}">
        </div>
        <div class="form-group">
            <label for="Question">Вопрос</label>
            <input type="text" class="form-control" name="Question" id="Question" value="{{ $question->Question }}" required>
        </div>
        <div class="form-group">
            <label for="Type">Тип вопроса</label>
            <select name="Type" id="Type" class="form-control">
                @foreach(getTypes() as $type => $content)
                    <option value="{{ $type }}" @if($question->Type == $type) selected @endif>{{ $content }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="Number">Номер вопроса</label>
            <input type="number" class="form-control" name="Number" id="Number" value="{{ $question->pivot->Number }}"
                   min="1" required>
        </div>
        <div class="form-group">
            <label for="Time">Количество секунд на вопрос (0 - не ограничено)</label>
            <input type="number" class="form-control" name="Time" id="Time" value="{{ $question->Time }}" min="0" required>
        </div>
        <div class="form-group">
            <label for="Mark">Количество баллов за вопрос</label>
            <input type="number" class="form-control" name="Mark" id="Mark" value="{{ $question->pivot->Mark }}" min="0" required>
        </div>
        <button type="submit" class="btn btn-success">Сохранить</button>
    </form>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="/dashboard/tests">Тесты</a></li>
    <li class="breadcrumb-item"><a href="/dashboard/tests/{{ $test->ID }}">{{ $test->Name }}</a></li>
    <li class="breadcrumb-item"><a href="/dashboard/tests/{{ $test->ID }}/questions">Вопросы теста</a></li>
    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
@endsection