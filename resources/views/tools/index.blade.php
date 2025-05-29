@extends('layouts.app')
@section('title', 'Free AI Tools')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Free AI Tools</h2>

    @forelse($grouped as $type => $tools)
        <div class="mb-5">
            <h4 class="text-primary">{{ $type ?? 'General' }}</h4>
            <div class="row">
                @foreach($tools as $tool)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $tool->name }}</h5>
                                <p class="card-text">{{ $tool->description }}</p>
                                <a href="{{ $tool->url }}" class="btn btn-sm btn-outline-primary" target="_blank">Visit Tool</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <p>No tools available at the moment.</p>
    @endforelse
</div>
@endsection
