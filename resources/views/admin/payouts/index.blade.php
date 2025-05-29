@extends('layouts.app')
@section('title', 'Payout Requests')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Payout Requests</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Payouts</li>
    </ol>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Affiliate</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payouts as $payout)
                        <tr>
                            <td>{{ $payout->affiliate->user->name ?? '-' }}</td>
                            <td>${{ number_format($payout->amount, 2) }}</td>
                            <td>{{ $payout->method }}</td>
                            <td>
                                <span class="badge bg-{{ $payout->status === 'paid' ? 'success' : ($payout->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($payout->status) }}
                                </span>
                            </td>
                            <td>{{ $payout->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                @if ($payout->status === 'pending')
                                    <form action="{{ route('admin.payouts.update', $payout) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="paid">
                                        <button class="btn btn-sm btn-success">Mark Paid</button>
                                    </form>
                                    <form action="{{ route('admin.payouts.update', $payout) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                @else
                                    <em>N/A</em>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if ($payouts->isEmpty())
                        <tr><td colspan="6" class="text-center">No payout requests.</td></tr>
                    @endif
                </tbody>
            </table>
            {{ $payouts->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
