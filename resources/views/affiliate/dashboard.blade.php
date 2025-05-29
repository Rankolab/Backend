@extends('layouts.app')
@section('title', 'Affiliate Dashboard')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Affiliate Dashboard</h2>

    <div class="alert alert-success">
        <strong>Your Referral Link:</strong><br>
        <input type="text" readonly class="form-control mt-2" value="{{ $referralUrl }}">
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h5>Referrals</h5>
                    <h2>{{ $referrals->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h5>Total Earned</h5>
                    <h2>${{ number_format($affiliate->total_earned, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3">Referral Activity</h5>
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Referred User</th>
                        <th>Commission</th>
                        <th>Converted</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($referrals as $ref)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ref->referredUser->email ?? '—' }}</td>
                            <td>${{ number_format($ref->commission, 2) }}</td>
                            <td>{{ $ref->converted_at ?? 'Pending' }}</td>
                            <td>{{ $ref->ip_address }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No referrals yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<hr class="my-4">

<h4>Payout Request</h4>
<form method="POST" action="{{ route('affiliate.payout.request') }}" class="mb-4">
    @csrf
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <label>Amount</label>
            <input type="number" name="amount" step="0.01" max="{{ $affiliate->total_earned }}" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label>Method</label>
            <select name="method" class="form-select" required>
                <option value="PayPal">PayPal</option>
                <option value="Bank">Bank Transfer</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Request</button>
        </div>
    </div>
</form>

<h5 class="mt-4">Payout History</h5>
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>Date</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($payouts as $payout)
            <tr>
                <td>{{ $payout->created_at->format('M d, Y') }}</td>
                <td>${{ number_format($payout->amount, 2) }}</td>
                <td>{{ $payout->method }}</td>
                <td><span class="badge bg-{{ $payout->status === 'paid' ? 'success' : ($payout->status === 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($payout->status) }}
                </span></td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center">No payouts yet.</td></tr>
        @endforelse
    </tbody>
</table>

@endsection
