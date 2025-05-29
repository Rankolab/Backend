@extends('layouts.app')
@section('title', 'Purchase Success')
@section('content')
<div class="container py-5 text-center">
    <h2 class="text-success">Thank You!</h2>
    <p>Your license has been generated successfully:</p>
    <div class="alert alert-primary d-inline-block">
        <strong>{{ $key }}</strong>
    </div>
    <p class="mt-3">You can now use it inside your Rankolab plugin setup wizard.</p>
    <a href="{{ url('/') }}" class="btn btn-outline-primary mt-3">Go to Dashboard</a>
</div>
@endsection
