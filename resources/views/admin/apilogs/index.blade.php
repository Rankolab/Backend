@extends('layouts.app')
@section('title', 'API Logs')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">API Usage Logs</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">API Logs</li>
    </ol>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped table-bordered align-middle small">
                <thead class="table-dark">
                    <tr>
                        <th>Source</th>
                        <th>Endpoint</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Time</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->source }}</td>
                            <td>{{ $log->endpoint }}</td>
                            <td><span class="badge bg-info">{{ $log->method }}</span></td>
                            <td>
                                <span class="badge bg-{{ $log->status_code < 300 ? 'success' : ($log->status_code < 500 ? 'warning' : 'danger') }}">
                                    {{ $log->status_code }}
                                </span>
                            </td>
                            <td>{{ $log->response_time ?? '-' }}</td>
                            <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
