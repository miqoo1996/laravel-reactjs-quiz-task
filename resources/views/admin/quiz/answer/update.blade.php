@extends('layouts.admin')

@section('content')
    <div>
        <h1>Update Answer for Question</h1>
    </div>

    <div>
        @include('admin.quiz.answer._form ')
    </div>
@endsection
