@extends('layouts.app')
@section('title', 'Free AI Tools')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Free AI Tools</h1>
    <a href="{{ route('admin.aitools.create') }}" class="btn btn-primary mb-3">Add New Tool</a>
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>URL</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tools as $tool)
                    <tr>
                        <td>{{ $tool->name }}</td>
                        <td>{{ $tool->category ?? '-' }}</td>
                        <td><span class="badge bg-{{ $tool->status === 'active' ? 'success' : 'secondary' }}">{{ $tool->status }}</span></td>
                        <td><a href="{{ $tool->url }}" target="_blank">{{ $tool->url }}</a></td>
                        <td>
                            <a href="{{ route('admin.aitools.edit', $tool) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" action="{{ route('admin.aitools.destroy', $tool) }}" class="d-inline">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this tool?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $tools->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
