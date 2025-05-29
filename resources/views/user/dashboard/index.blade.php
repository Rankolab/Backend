@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="container py-4">
    <h2>Welcome, {{ $user->name }}</h2>
    <p class="lead text-muted">Here’s a quick overview of your Rankolab activity.</p>

    <div class="row g-4 mt-3">
        <div class="col-md-4">
            <div class="card border-start border-primary border-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Websites</h5>
                    <p class="display-6">{{ $stats['total_websites'] }}</p>
                
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="alert alert-{{ \Carbon\Carbon::parse($stats['license_expiry'])->isPast() ? 'danger' : 'info' }}">
                <strong>License Expiry:</strong>
                {{ \Carbon\Carbon::parse($stats['license_expiry'])->diffForHumans() ?? 'N/A' }}
            
    <div class="mt-4">
        <h4>Recent Website SEO Metrics</h4>
        <div class="row g-3">
            @forelse ($websites as $site)
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title">{{ $site->domain }}</h5>
                            <p class="mb-1"><strong>Domain Authority:</strong> {{ $site->metrics->domain_authority ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>PageSpeed Score:</strong> {{ $site->metrics->page_speed ?? 'N/A' }}</p>
                            <p class="text-muted"><small>Last Checked: {{ $site->metrics->updated_at?->format('M d, Y H:i') ?? 'Never' }}</small></p>
                        
    <div class="mt-4">
        <h4>Recent Search Queries</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Query</th>
                        <th>Clicks</th>
                        <th>Impressions</th>
                        <th>CTR (%)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->websites->flatMap->searchMetrics->sortByDesc('date')->take(10) as $metric)
                        <tr>
                            <td>{{ $metric->query }}</td>
                            <td>{{ $metric->clicks }}</td>
                            <td>{{ $metric->impressions }}</td>
                            <td>{{ number_format($metric->ctr, 2) }}</td>
                            <td>{{ $metric->date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
</div>
                    </div>
                </div>
            @empty
                <div class="col-12"><p>No websites with metrics found.</p></div>
            @endforelse
        </div>
    </div>
    
</div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-{{ $stats['plugin_connected'] ? 'success' : 'secondary' }}">
                <strong>Plugin Status:</strong>
                {{ $stats['plugin_connected'] ? 'Connected' : 'Not Connected' }}
            </div>
        </div>
    </div>
    
</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-success border-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Active Licenses</h5>
                    <p class="display-6">{{ $stats['total_licenses'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-warning border-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Articles Generated</h5>
                    <p class="display-6">{{ $stats['articles_generated'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
