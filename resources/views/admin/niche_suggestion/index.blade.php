@extends("layouts.admin")

@section("content")
<div class="container-fluid">
    <h1 class="mt-4">Niche Suggestion Tool</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Niche Suggestion</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-lightbulb me-1"></i>
            Find Profitable Niches
        </div>
        <div class="card-body">
            @include("partials.flash_messages")
            @include("partials.validation_errors")

            <p>Enter keywords or topics to get suggestions for potentially profitable website niches.</p>
            
            <form action="{{ route("admin.niche.suggestion.suggest") }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="keywords" class="form-label">Keywords / Topics</label>
                    <input type="text" class="form-control" id="keywords" name="keywords" placeholder="e.g., sustainable gardening, drone photography, home fitness" value="{{ old("keywords") }}" required>
                </div>

                {{-- Add options like region, difficulty, search volume filters --}}
                {{-- <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="region" class="form-label">Region (Optional)</label>
                        <input type="text" class="form-control" id="region" name="region" placeholder="e.g., USA, UK" value="{{ old("region") }}">
                    </div>
                    <div class="col-md-6">
                        <label for="difficulty" class="form-label">Max Difficulty (Optional)</label>
                        <select class="form-control" id="difficulty" name="difficulty">
                            <option value="">Any</option>
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                </div> --}}

                <button class="btn btn-primary" type="submit">Get Niche Suggestions</button>
            </form>

            {{-- Placeholder for displaying suggestions --}}
            <div id="niche-suggestions" class="mt-4">
                @if (session("niche_suggestions"))
                    <h5>Suggested Niches:</h5>
                    <ul class="list-group">
                        @foreach (session("niche_suggestions") as $suggestion)
                            <li class="list-group-item">
                                <strong>{{ $suggestion["niche"] }}</strong><br>
                                <small>Potential: {{ $suggestion["potential"] ?? "N/A" }} | Difficulty: {{ $suggestion["difficulty"] ?? "N/A" }}</small><br>
                                <small>Keywords: {{ implode(", ", $suggestion["related_keywords"] ?? []) }}</small>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- Optional: List recent suggestion history --}}
    {{-- <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-history me-1"></i>
            Suggestion History
        </div>
        <div class="card-body">
            <p class="text-muted">Niche suggestion history will be shown here.</p>
        </div>
    </div> --}}

</div>
@endsection

