@extends('layouts.admin')

@section('content')
    <div>
        <div>
            <div class="float-right">
                <a href="{{ route('admin.quiz.question.create', $quizId) }}" class="btn btn-lg btn-primary" style="margin: 20px;">Create</a>
                <a href="{{ route('admin.quiz.index') }}" class="btn btn-lg btn-dark" style="margin: 20px;">Back to Quizzes</a>
            </div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Mode</th>
                <th>Quiz</th>
                <th>Title</th>
                <th>Buttons</th>
            </tr>
            </thead>
            <tbody>
            @forelse($list as $item)
                <tr>
                    <td>
                        {{ $item->id }}
                    </td>
                    <td>
                        {{ $item->mode }}
                    </td>
                    <td>
                        {{ $item->quiz->title }}
                    </td>
                    <td>
                        {{ $item->title }}
                    </td>
                    <td>
                        <a class="btn btn-info" href="{{ route('admin.quiz.answer.index', $item) }}">List Answers</a>
                        <a class="btn btn-primary" href="{{ route('admin.quiz.question.update', $item) }}">Update</a>
                        <a class="btn btn-danger" href="{{ route('admin.quiz.question.delete', $item) }}">Delete</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <h1 class="text-center">No data found</h1>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{ $list->links('pagination::bootstrap-4') }}
    </div>
@endsection
