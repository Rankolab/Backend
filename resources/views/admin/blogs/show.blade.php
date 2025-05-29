@extends('layouts.app')
@section('title', $blog->title)

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $blog->title }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blogs</a></li>
        <li class="breadcrumb-item active">View</li>
    </ol>

    <div class="card">
        <div class="card-body">
            <p><strong>Status:</strong> {{ ucfirst($blog->status) }}</p>
            <p><strong>Author:</strong> {{ $blog->author->name ?? '-' }}</p>
            <p><strong>Published:</strong> {{ $blog->published_at ?? '-' }}</p>
            <p><strong>Excerpt:</strong> {{ $blog->excerpt }}</p>
            <hr>
            <p>{!! nl2br(e($blog->body)) !!}</p>
        </div>
    </div>
</div>
@endsection
