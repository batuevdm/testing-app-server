@extends('layout.app')

@section('title')
    Результаты тестов
@endsection

@section('content')
    <h1>Результаты тестов</h1>
    @include('includes.tests', $tests)
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
@endsection