@extends('layouts.app')

@section('title', 'Articles')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Articles</h4>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add New Article
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Published</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $article->title }}</td>
                            <td>
                                @if($article->is_published)
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $article->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.articles.show', $article->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this article?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">No articles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
