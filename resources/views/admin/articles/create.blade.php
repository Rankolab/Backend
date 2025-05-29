@extends('layouts.app')

@section('title', 'Create New Article')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Create New Article</h4>
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

    <form action="{{ route('admin.articles.store') }}" method="POST">
        @csrf
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Article Title</label>
                    <input type="text" name="title" class="form-control" id="title" placeholder="Enter article title" required>
                </div>

                <div class="mb-3">
                    <label for="body" class="form-label">Content</label>
                    <textarea name="body" class="form-control" id="body" rows="8" placeholder="Write article content here..." required></textarea>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" name="is_published" id="is_published">
                    <label class="form-check-label" for="is_published">
                        Publish immediately
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Create Article</button>
            </div>
        </div>
    </form>
</div>
@endsection
