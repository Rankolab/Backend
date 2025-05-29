@extends('layouts.app')

@section('title', 'Affiliate Dashboard')

@section('content')
<div class="container mt-4">
    <h1>Affiliate Program</h1>

    <div class="card mb-4">
        <div class="card-body">
            <p>Your Referral Link:</p>
            <div class="input-group">
                <input type="text" readonly class="form-control" value="{{ url('/') }}?ref={{ $affiliate->referral_code }}">
                <button class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ url('/') }}?ref={{ $affiliate->referral_code }}')">Copy</button>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5>Clicks</h5>
                    <h2>{{ $affiliate->clicks }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5>Signups</h5>
                    <h2>{{ $affiliate->referred_count }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5>Total Earnings</h5>
                    <h2>${{ number_format($affiliate->total_earnings, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <form method="POST" action="{{ route('user.affiliate.payout') }}">
                @csrf
                <button class="btn btn-warning w-100 mt-4" {{ $affiliate->payout_requested ? 'disabled' : '' }}>
                    {{ $affiliate->payout_requested ? 'Payout Requested' : 'Request Payout' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
