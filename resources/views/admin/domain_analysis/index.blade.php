@extends("layouts.admin")

@section("content")
<div class="container-fluid">
    <h1 class="mt-4">Domain Analysis</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Domain Analysis</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-search-dollar me-1"></i>
            Analyze Domain
        </div>
        <div class="card-body">
            @include("partials.flash_messages")
            @include("partials.validation_errors")

            <p>Enter a domain name to analyze its potential, SEO metrics, and competitive landscape.</p>
            
            <form action="{{ route("admin.domain.analysis.analyze") }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Enter domain name (e.g., example.com)" name="domain" value="{{ old("domain") }}" required>
                    <button class="btn btn-primary" type="submit">Analyze</button>
                </div>
            </form>

            {{-- Placeholder for displaying analysis results or a link to them --}}
            {{-- This section might be populated via AJAX or redirect to a results page --}}
            <div id="analysis-results" class="mt-4">
                {{-- Results will be loaded here or user will be redirected --}}
            </div>
        </div>
    </div>

    {{-- Optional: List recent analyses --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-history me-1"></i>
            Recent Analyses
        </div>
        <div class="card-body">
            {{-- TODO: Fetch and display recent domain analysis records --}}
            <p class="text-muted">Recent analysis history will be shown here.</p>
            {{-- Example Table Structure --}}
            {{-- <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Domain</th>
                        <th>Analyzed At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>example.com</td>
                        <td>2025-05-24 10:00:00</td>
                        <td>Completed</td>
                        <td><a href="#" class="btn btn-info btn-sm">View Results</a></td>
                    </tr>
                </tbody>
            </table> --}}
        </div>
    </div>

</div>
@endsection

