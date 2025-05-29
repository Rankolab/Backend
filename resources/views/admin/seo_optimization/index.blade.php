@extends("layouts.admin")

@section("content")
<div class="container-fluid">
    <h1 class="mt-4">SEO Optimization Analysis</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item active">SEO Optimization</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-line me-1"></i>
            Analyze Page or Website SEO
        </div>
        <div class="card-body">
            @include("partials.flash_messages")
            @include("partials.validation_errors")

            <p>Enter a specific URL or select a managed website to analyze its on-page and technical SEO factors.</p>
            
            <form action="{{ route("admin.seo.optimization.analyze") }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="analysis_target" class="form-label">Target URL or Website</label>
                    <input type="text" class="form-control" id="analysis_target" name="analysis_target" placeholder="Enter full URL (e.g., https://example.com/page) or select website" value="{{ old("analysis_target") }}" required>
                    {{-- Optionally, add a dropdown to select from managed websites --}}
                    {{-- <select class="form-control mt-2" name="website_id">
                        <option value="">Or Select Managed Website...</option>
                        @foreach (\App\Models\Website::where("user_id", auth()->id())->orWhereNull("user_id")->get() as $website) {{-- Adjust query as needed --}}
                            <option value="{{ $website->website_id }}">{{ $website->domain }}</option>
                        @endforeach
                    </select> --}}
                </div>

                {{-- Add options for analysis depth or specific checks --}}
                {{-- <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="check_technical" name="checks[]" value="technical" checked>
                    <label class="form-check-label" for="check_technical">Technical SEO</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="check_onpage" name="checks[]" value="onpage" checked>
                    <label class="form-check-label" for="check_onpage">On-Page Content</label>
                </div> --}}

                <button class="btn btn-primary" type="submit">Analyze SEO</button>
            </form>

            {{-- Placeholder for displaying analysis results --}}
            <div id="seo-analysis-results" class="mt-4">
                {{-- Results will be loaded here or user will be redirected --}}
            </div>
        </div>
    </div>

    {{-- Optional: List recent SEO analyses --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-history me-1"></i>
            Recent SEO Analyses
        </div>
        <div class="card-body">
            {{-- TODO: Fetch and display recent SEO analysis records --}}
            <p class="text-muted">Recent SEO analysis history will be shown here.</p>
        </div>
    </div>

</div>
@endsection

