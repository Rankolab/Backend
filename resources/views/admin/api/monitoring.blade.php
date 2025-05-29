@extends("layouts.app")

@section("title", "API Monitoring")

@section("content")
<div class="container-fluid px-4">
    <h1 class="mt-4">API Monitoring</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item active">API Monitoring</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">Monitored APIs Status</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Response Time (ms)</th>
                        <th>Last Checked</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monitoredApis as $monitoredApi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            {{-- Assuming MonitoredApi has a relationship named apiService --}}
                            <td>{{ $monitoredApi->apiService->name ?? "N/A" }}</td>
                            <td>{{ ucfirst($monitoredApi->apiService->category ?? "N/A") }}</td>
                            <td><span class="badge bg-{{ $monitoredApi->status == "online" ? "success" : ($monitoredApi->status == "slow" ? "warning" : "danger") }}">{{ ucfirst($monitoredApi->status) }}</span></td>
                            <td>{{ $monitoredApi->response_time_ms ?? "N/A" }}</td>
                            <td>{{ $monitoredApi->last_checked_at ? $monitoredApi->last_checked_at->diffForHumans() : "Never" }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No APIs are currently being monitored.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add section for API Logs if needed, using the $apiLogs variable --}}
    <div class="card">
        <div class="card-header">Recent API Call Statistics (Last 30 Days)</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>API Service</th>
                        <th>Total Calls</th>
                        <th>Avg. Response Time (ms)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($apiLogs as $log)
                        <tr>
                            {{-- Find the service name from $apiServices based on $log->api_service_id --}}
                            <td>{{ $apiServices->firstWhere("id", $log->api_service_id)->name ?? "Unknown Service" }}</td>
                            <td>{{ $log->total_calls }}</td>
                            <td>{{ number_format($log->avg_response_time, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No API logs found in the last 30 days.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

