@extends('layouts.app')
@section('title', 'Purchase Logs')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Purchase Logs</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Purchases</li>
    </ol>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Purchased At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->user->name ?? '-' }}</td>
                            <td>${{ $purchase->amount }}</td>
                            <td>{{ strtoupper($purchase->currency) }}</td>
                            <td>
                                <span class="badge bg-{{ $purchase->status === 'completed' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                            <td>{{ $purchase->purchased_at ?? $purchase->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No purchases found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $purchases->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
