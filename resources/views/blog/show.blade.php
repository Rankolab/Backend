@extends('layouts.app')
@section('title', $blog->title)

@section('content')
<div class="container py-4">
    <h2 class="mb-3">{{ $blog->title }}</h2>
    <div class="text-muted mb-2">
        Published on {{ \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') }}
    </div>
    <p class="lead">{{ $blog->excerpt }}</p>
    <hr>
    <article class="mb-5">
        {!! nl2br(e($blog->body)) !!}
    </article>
    <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary">Back to Blog</a>
</div>
@endsection
