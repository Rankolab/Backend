@extends('layouts.app')
@section('title', 'Admin Alerts')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">System Alerts & Notifications</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Notifications</li>
    </ol>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Status</th>
                        <th>Message</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifications as $note)
                        <tr>
                            <td>
                                <span class="badge bg-{{ $note->is_read ? 'secondary' : 'warning' }}">
                                    {{ $note->is_read ? 'Read' : 'Unread' }}
                                </span>
                            </td>
                            <td>{{ $note->message }}</td>
                            <td>{{ ucfirst($note->type) }}</td>
                            <td>{{ $note->created_at->format('M d, Y H:i') }}</td>
                            <td class="text-end">
                                @if (! $note->is_read)
                                    <form method="POST" action="{{ route('admin.notifications.read', $note) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success">Mark as Read</button>
                                    </form>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No notifications.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
