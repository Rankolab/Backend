@extends('layouts.app')
@section('title', 'License Plans')
@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4">Choose Your Rankolab Plan</h2>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Pro License</h4>
                </div>
                <div class="card-body text-center">
                    <h2>$99</h2>
                    <p class="text-muted">1 Year, 1 Website, Full Features</p>
                    <form method="POST" action="{{ route('license.checkout') }}">
                        @csrf
                        <button class="btn btn-success w-100">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
