@extends('layouts.app')
@section('title', 'My Licenses')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">My Rankolab Licenses</h2>

    @forelse ($licenses as $license)
        <div class="card mb-3">
            <div class="card-body">
                <h5>License Key:</h5>
                <p class="text-monospace bg-light p-2">{{ $license->license_key }}</p>

                <div class="row">
                    <div class="col-md-4">
                        <strong>Type:</strong> {{ ucfirst($license->type) }}
                    </div>
                    <div class="col-md-4">
                        <strong>Status:</strong> <span class="badge bg-{{ $license->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($license->status) }}</span>
                    </div>
                    <div class="col-md-4">
                        <strong>Expires:</strong> {{ \Carbon\Carbon::parse($license->expiry_date)->format('M d, Y') }}
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">You don't have any active licenses yet.</div>
    @endforelse
</div>
@endsection
