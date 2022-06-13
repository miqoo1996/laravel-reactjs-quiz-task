@extends('layouts.admin')

@section('content')
    <div>
        <div>
            <div class="float-right">
                <a href="{{ route('admin.quiz.answer.create', $questionId) }}" class="btn btn-lg btn-primary" style="margin: 20px;">Create</a>
                <a href="{{ route('admin.quiz.question.index', $question->quiz_id) }}" class="btn btn-lg btn-dark" style="margin: 20px;">Back to Questions</a>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Question</th>
                <th>Title</th>
                <th>Is Correct?</th>
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
                        {{ $item->question->title }}
                    </td>
                    <td>
                        {{ $item->title }}
                    </td>
                    <td>
                        {{ $item->is_correct ? 'Yes' : 'No' }}
                    </td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('admin.quiz.answer.update', $item) }}">Update</a>
                        @if($item->question->mode !== \App\Models\Quiz\QuizQuestion::MODE_DEFAULT_BINARY)
                            <a class="btn btn-danger" href="{{ route('admin.quiz.answer.delete', $item) }}">Delete</a>
                        @endif
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
