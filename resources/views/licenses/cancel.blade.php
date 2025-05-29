@extends('layouts.app')
@section('title', 'Purchase Cancelled')
@section('content')
<div class="container py-5 text-center">
    <h2 class="text-warning">Payment Cancelled</h2>
    <p>No worries! You can return anytime to choose a license.</p>
    <a href="{{ route('license.plans') }}" class="btn btn-outline-secondary mt-3">Back to Plans</a>
</div>
@endsection
