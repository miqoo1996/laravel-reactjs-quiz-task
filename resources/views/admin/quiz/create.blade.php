@extends('layouts.admin')

@section('content')
    <div>
        <h1>Create Quiz</h1>
    </div>

    <div>
        @include('admin.quiz._form')
    </div>
@endsection
