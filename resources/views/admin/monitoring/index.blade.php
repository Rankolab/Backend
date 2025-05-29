@extends('layouts.app')
@section('title', 'API Health Monitor')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">API Monitoring</h1>
    <form method="POST" action="{{ route('admin.monitoring.store') }}" class="mb-4">@csrf
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="API Name" required>
            </div>
            <div class="col-md-6">
                <input type="url" name="endpoint" class="form-control" placeholder="API Endpoint URL" required>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Add API</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>API</th>
                <th>URL</th>
                <th>Status</th>
                <th>Last Checked</th>
                <th>Response Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($apis as $api)
            <tr>
                <td>{{ $api->name }}</td>
                <td><a href="{{ $api->endpoint }}" target="_blank">{{ $api->endpoint }}</a></td>
                <td>
                    <span class="badge bg-{{ $api->status === 'online' ? 'success' : 'danger' }}">{{ ucfirst($api->status) }}</span>
                </td>
                <td>{{ $api->last_checked_at ? $api->last_checked_at->diffForHumans() : '-' }}</td>
                <td>{{ $api->response_time_ms ? $api->response_time_ms . ' ms' : '-' }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.monitoring.check', $api) }}" class="d-inline">@csrf
                        <button class="btn btn-sm btn-info">Check</button>
                    </form>
                    <form method="POST" action="{{ route('admin.monitoring.destroy', $api) }}" class="d-inline">@csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
