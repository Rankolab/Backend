@extends('layouts.app')
@section('title', 'Blog')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Latest Blog Posts</h2>
    <div class="row">
        @forelse ($blogs as $blog)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $blog->title }}</h5>
                        <p class="card-text text-muted">{{ $blog->excerpt }}</p>
                        <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-primary">Read More</a>
                    </div>
                    <div class="card-footer text-muted small">
                        Published on {{ \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>No blog posts found.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-4">
        {{ $blogs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
