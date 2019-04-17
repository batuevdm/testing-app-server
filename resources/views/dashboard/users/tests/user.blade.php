@extends('layout.app')

@section('title')
    Результаты тестов
@endsection

@section('content')
    <h1>Результаты тестов пользователя {{ $user->Login }}</h1>
    @include('includes.tests', $tests)
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="/dashboard/users">Пользователи</a></li>
    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
@endsection