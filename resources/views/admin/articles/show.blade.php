@extends('layouts.app')

@section('title', 'View Article')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Article Details</h4>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">← Back to Articles</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">{{ $article->title }}</h5>

            <div class="mb-3">
                <span class="badge {{ $article->is_published ? 'bg-success' : 'bg-secondary' }}">
                    {{ $article->is_published ? 'Published' : 'Draft' }}
                </span>
            </div>

            <div class="mb-3">
                <small class="text-muted">Created: {{ $article->created_at->format('d M Y, H:i') }}</small>
                <br>
                <small class="text-muted">Updated: {{ $article->updated_at->format('d M Y, H:i') }}</small>
            </div>

            <div class="mb-4">
                <h6>Content:</h6>
                <div class="border rounded p-3" style="background: #f8f9fa;">
                    {!! nl2br(e($article->body)) !!}
                </div>
            </div>

            <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-warning">Edit</a>

            <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this article?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
