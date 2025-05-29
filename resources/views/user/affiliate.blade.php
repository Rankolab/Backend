@extends('layouts.app')

@section('title', 'Affiliate Dashboard')

@section('content')
<div class="container py-4">
    <h2>Affiliate Dashboard</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p>Your referral link:</p>
            <div class="input-group">
                <input type="text" id="refLink" class="form-control" value="{{ url('/') }}?ref={{ $affiliate->ref_code }}" readonly>
                <button onclick="copyLink()" class="btn btn-outline-primary">Copy</button>
            </div>
        </div>
    </div>

    <script>
    function copyLink() {
        var copyText = document.getElementById("refLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Referral link copied!");
    }
    </script>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h5>Total Referrals</h5>
                    <h2>{{ $referrals->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body">
                    <h5>Conversions</h5>
                    <h2>{{ $referrals->where('converted', true)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h5>Total Earnings</h5>
                    <h2>$ {{ number_format($commissions->sum('amount'), 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <h4>Referrals</h4>
    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>IP</th>
                <th>Converted</th>
                <th>Registered User</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($referrals as $ref)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $ref->ip_address }}</td>
                    <td>{{ $ref->converted ? 'Yes' : 'No' }}</td>
                    <td>{{ $ref->referredUser->email ?? '-' }}</td>
                    <td>{{ $ref->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No referrals yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
