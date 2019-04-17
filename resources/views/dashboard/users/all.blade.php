@extends('layout.app')

@section('title')
    Пользователи
@endsection

@section('content')
    <h1>Пользователи</h1>
    @if(count($users) > 0)
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Фамилия</th>
                <th scope="col">Имя</th>
                <th scope="col">Логин</th>
                <th scope="col">Должность</th>
                <th scope="col"> </th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        {{ $user->ID }}
                    </td>
                    <td>
                        {{ $user->LastName }}
                    </td>
                    <td>
                        {{ $user->FirstName }}
                    </td>
                    <td>
                        {{ $user->Login }}
                    </td>
                    <td>
                        {{ $user->Role }}
                    </td>
                    <td>
                        <a href="/dashboard/users/{{ $user->ID }}/tests" class="btn btn-warning">Результаты тестов</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Нет пользователей</p>
    @endif
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
@endsection