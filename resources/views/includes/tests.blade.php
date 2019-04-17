@if(count($tests) > 0)
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Пользователь</th>
            <th scope="col">Тест</th>
            <th scope="col">Правильных ответов</th>
            <th scope="col">Дата</th>
            <th scope="col">Статус</th>
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
                    {{ $test->User }}
                </td>
                <td>
                    {{ $test->Test }}
                </td>
                <td>
                    {{ $test->RightAnswers }}
                </td>
                <td>
                    {{ $test->StartDate }}
                </td>
                <td>
                    {{ $test->Status }}
                </td>
                <td>
                    <a href="/dashboard/users/tests/{{ $test->ID }}" class="btn btn-warning">Подробнее</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>Нет тестов</p>
@endif