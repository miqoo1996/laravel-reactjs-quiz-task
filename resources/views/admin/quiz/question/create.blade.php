@extends('layouts.admin')

@section('content')
    <div>
        <h1>Create Question</h1>
    </div>

    <div>
        @include('admin.quiz.question._form')
    </div>
@endsection
