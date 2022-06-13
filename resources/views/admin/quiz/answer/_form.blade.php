@if(!empty($errors->all()))
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="post" action="{{ route('admin.quiz.answer.save', $model) }}">
    @csrf

    <input type="hidden" name="quizQuestionId" value="{{ old('quizQuestionId', $model->quiz_question_id) }}" class="form-control">

    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title', $model->title) }}" class="form-control">
    </div>

    <div class="form-group" style="display: flex; align-items: baseline; column-gap: 20px;">
        <label for="is_correct">Is Correct</label>
        <input type="checkbox" name="is_correct" id="is_correct" value="1" {{ old('is_correct', $model->is_correct) ? 'checked' : '' }}>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
