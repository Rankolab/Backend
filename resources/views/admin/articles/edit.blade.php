@extends('layouts.app')

@section('title', 'Edit Article')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Article</h4>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">← Back to Articles</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the errors below:<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Article Title</label>
                    <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $article->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="body" class="form-label">Content</label>
                    <textarea name="body" class="form-control" id="body" rows="8" required>{{ old('body', $article->body) }}</textarea>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" name="is_published" id="is_published" {{ $article->is_published ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">
                        Published
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Update Article</button>
            </div>
        </div>
    </form>
</div>
@endsection
