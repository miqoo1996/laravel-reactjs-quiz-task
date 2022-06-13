@if(!empty($errors->all()))
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="post" action="{{ route('admin.quiz.save', $model) }}">
    @csrf

    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title', $model->title) }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Duration</label>
        <input type="number" name="duration" class="form-control" value="{{ old('duration', $model->duration ?: \App\Models\Quiz\Quiz::DEFAULT_DURATION) }}">
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="5" class="form-control">{{ old('description', $model->description) }}</textarea>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
