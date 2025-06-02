@extends("layouts.admin")

@section("title", "API Management")

@section("content")
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">API Management</h1>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">API Sections</h6>
                </div>
                <div class="card-body">
                    <p>Manage and monitor your application\'s API integrations.</p>
                    <ul>
                        {{-- Check if routes exist before creating links --}}
                        @if (Route::has("admin.api.monitoring"))
                            <li><a href="{{ route("admin.api.monitoring") }}">API Monitoring</a></li>
                        @endif
                        @if (Route::has("admin.api.keys"))
                            <li><a href="{{ route("admin.api.keys") }}">API Keys</a></li>
                        @endif
                         @if (Route::has("admin.apilogs.index")) {{-- Assuming logs route is named this based on web.php --}}
                            <li><a href="{{ route("admin.apilogs.index") }}">API Logs</a></li>
                        @endif
                        @if (Route::has("admin.api.analytics"))
                            <li><a href="{{ route("admin.api.analytics") }}">API Analytics</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

