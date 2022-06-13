@extends('layouts.admin')

@section('content')
    <div>
        <div>
            <div class="float-right">
                <a href="{{ route('admin.quiz.create') }}" class="btn btn-lg btn-primary" style="margin: 20px;">Create</a>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Duration</th>
                <th>Description</th>
                <th>Buttons</th>
            </tr>
            </thead>
            <tbody>
            @forelse($list as $quiz)
                <tr>
                    <td>
                        {{ $quiz->id }}
                    </td>
                    <td>
                        {{ $quiz->title }}
                    </td>
                    <td>
                        {{ $quiz->duration }}
                    </td>
                    <td>
                        {{ $quiz->description }}
                    </td>
                    <td>
                        <a class="btn btn-info" href="{{ route('admin.quiz.question.index', $quiz) }}">List Questions</a>
                        <a class="btn btn-primary" href="{{ route('admin.quiz.update', $quiz) }}">Update</a>
                        <a class="btn btn-danger" href="{{ route('admin.quiz.delete', $quiz) }}">Delete</a>
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
