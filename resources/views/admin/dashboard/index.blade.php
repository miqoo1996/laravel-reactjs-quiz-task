@extends('layouts.admin')

@section('content')
    <div>
        <h1>Hello {{ auth()->user()->name }}!</h1>
        <hr />
    </div>

    <div style="margin-top: 50px">
        <h2>Quiz Statistics</h2>
    </div>

    <div style="margin-top: 25px">
        <table class="table">
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>User name</th>
                    <th>Email</th>
                    <th>Total Score</th>
                    <th>Total Number of unanswered questions</th>
                    <th>Quiz Submit Date / Time</th>
                    <th>Time used on taking the quiz</th>
                </tr>
            </thead>
            <tbody>
            @foreach($statistics as $statistic)
                <tr>
                    <td>{{ $statistic->quiz->title }}</td>
                    <td>{{ $statistic->quizGuestUser->firstname . ' ' . $statistic->quizGuestUser->lastname }}</td>
                    <td>{{ $statistic->quizGuestUser->email }}</td>
                    <td>{{ $statistic->score }}</td>
                    <td>{{ $statistic->unanswered_count ?: '0' }}</td>
                    <td>{{ $statistic->submit_date }}</td>
                    <td>{{ $statistic->used_time_mins }} Minutes</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
