@extends('layout.app')

@section('title')
    Верные ответы
@endsection

@section('content')
    <h1>Верные ответы</h1>
    @if(count($answers) > 0)
        <form action="" method="post">
            @csrf
            @if($question->Type == 'QTypeCheckbox')
                @foreach($answers as $answer)
                    <div class="form-group">
                        <input type="checkbox" name="True[]" required value="{{ $answer->AnswerID }}"
                               @if($answer->IsTrue) checked @endif id="item-{{ $answer->AnswerID }}">
                        <label for="item-{{ $answer->AnswerID }}">{{ $answer->answer->Answer }}</label>
                    </div>
                @endforeach
            @endif
            @if($question->Type == 'QTypeRadio')
                @foreach($answers as $answer)
                    <div class="form-group">
                        <input type="radio" name="True[]" required value="{{ $answer->AnswerID }}"
                               @if($answer->IsTrue) checked @endif id="item-{{ $answer->AnswerID }}">
                        <label for="item-{{ $answer->AnswerID }}">{{ $answer->answer->Answer }}</label>
                    </div>
                @endforeach
            @endif
            <button type="submit" class="btn btn-success">Сохранить</button>
        </form>
    @endif
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="/dashboard/tests">Тесты</a></li>
    <li class="breadcrumb-item"><a href="/dashboard/tests/{{ $test->ID }}">{{ $test->Name }}</a></li>
    <li class="breadcrumb-item"><a href="/dashboard/tests/{{ $test->ID }}/questions">{{ $question->Question }}</a></li>
    <li class="breadcrumb-item"><a href="/dashboard/tests/{{ $test->ID }}/questions/{{ $question->ID }}/answers">Ответы
            на вопрос</a></li>
    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
@endsection