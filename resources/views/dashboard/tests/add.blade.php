@extends('layout.app')

@section('title')
    Новый тест
@endsection

@section('content')
    <h1>Новый тест</h1>
    <form action="" method="post">
        @csrf
        <div class="form-group">
            <label for="Name">Название</label>
            <input type="text" class="form-control" name="Name" id="Name" value="{{ old('Name') }}" required>
        </div>
        <div class="form-group">
            <label for="MinMarkPercent">Минимальный результат для получения сертификата (в процентах)</label>
            <input type="number" class="form-control" name="MinMarkPercent" id="MinMarkPercent" value="{{ old('MinMarkPercent') }}" min="0" max="100" required>
        </div>
        <div class="form-group">
            <input type="hidden" name="hide" value="0">
            <input type="checkbox" name="hide" id="hide" @if(old('hide')) checked @endif value="1">
            <label for="hide">Скрыть</label>
        </div>
        <button type="submit" class="btn btn-success">Сохранить</button>
    </form>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="/dashboard/tests">Тесты</a></li>
    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
@endsection