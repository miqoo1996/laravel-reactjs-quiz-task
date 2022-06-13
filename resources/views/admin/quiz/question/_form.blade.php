@if(!empty($errors->all()))
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="post" action="{{ route('admin.quiz.question.save', $model) }}">
    @csrf

    <input type="hidden" name="quizId" value="{{ old('quizId', $model->quiz_id) }}" class="form-control">

    <div class="form-group">
        <label>Mode</label>
        <select name="mode" class="form-control">
            @foreach(\App\Models\Quiz\QuizQuestion::MODES as $mode)
                <option value="{{ $mode }}" {{ old('mode', $model->mode) === $mode ? 'selected' : '' }}>{{ $mode }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title', $model->title) }}" class="form-control">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
