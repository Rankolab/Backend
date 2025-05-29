@extends("layouts.app")

@section("title", "Admin Dashboard")

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2 sm:mb-0">Dashboard Overview</h1>
        <div class="flex space-x-3">
            {{-- Placeholder for Filters --}}
            {{-- <div class="filter-dropdown">
                <button class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-filter mr-2"></i>
                    <span>Filters</span>
                </button>
            </div> --}}
            {{-- Placeholder for Export --}}
            {{-- <button class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-download mr-2"></i>
                <span>Export</span>
            </button> --}}
        </div>
    </div>

    <!-- Critical Alerts Placeholder -->
    {{-- @if($criticalAlerts && $criticalAlerts->isNotEmpty()) --}}
    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-exclamation-triangle mr-3"></i></div>
            <div>
                <p class="font-bold">Critical Alerts</p>
                <ul class="list-disc list-inside mt-1 text-sm">
                    {{-- @foreach($criticalAlerts as $alert) --}}
                    {{-- <li>{{ $alert->message }} <a href="{{-- route("admin.notifications.read", $alert->id) --}}" class="font-medium underline">Mark as read</a></li> --}}
                    <li>Placeholder Alert: High server load detected.</li> {{-- Example --}}
                    <li>Placeholder Alert: Database connection failed intermittently.</li> {{-- Example --}}
                    {{-- @endforeach --}}
                </ul>
            </div>
        </div>
    </div>
    {{-- @endif --}}

    <!-- Dashboard Widgets Container (for future drag-and-drop/customization) -->
    <div class="space-y-6" id="dashboard-widgets">

        <!-- Performance Overview Widget -->
        <div class="widget" data-widget-id="performance_overview">
            {{-- <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performance Overview</h2> --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-full p-3">
                        <i class="fas fa-users fa-lg text-white"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Users</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</div>
                        <a href="{{ route("admin.users.index") }}" class="text-xs font-medium text-blue-600 hover:text-blue-500 block mt-1">View Details &rarr;</a>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-full p-3">
                        <i class="fas fa-globe fa-lg text-white"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Websites</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalWebsites) }}</div>
                        <a href="{{ route("admin.websites.index") }}" class="text-xs font-medium text-green-600 hover:text-green-500 block mt-1">View Details &rarr;</a>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 flex items-center">
                    <div class="flex-shrink-0 bg-indigo-500 rounded-full p-3">
                        <i class="fas fa-file-alt fa-lg text-white"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Published Articles</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($publishedArticles) }}</div>
                        <a href="{{ route("admin.blogs.index") }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500 block mt-1">View Details &rarr;</a>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 flex items-center">
                    <div class="flex-shrink-0 bg-orange-500 rounded-full p-3">
                        <i class="fas fa-key fa-lg text-white"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Licenses</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($activeLicenses) }}</div>
                        <a href="{{ route("admin.licenses.index") }}" class="text-xs font-medium text-orange-600 hover:text-orange-500 block mt-1">View Details &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & System Health Widget -->
        <div class="widget" data-widget-id="charts_system_health">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Revenue Chart -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-md rounded-lg p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue Trends</h2>
                        {{-- Add controls for time period if needed --}}
                    </div>
                    <div class="h-64 md:h-72">
                        <canvas id="revenue-chart"></canvas>
                    </div>
                </div>

                <!-- System Health -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">System Health</h2>
                    <div class="space-y-5">
                        <div>
                            <div class="flex justify-between text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                <span>Server Load (1m/5m/15m)</span>
                            </div>
                            <div class="text-gray-900 dark:text-white font-semibold text-lg" id="system-load">
                                {{ number_format($loadAverage[0] ?? 0, 2) }} / {{ number_format($loadAverage[1] ?? 0, 2) }} / {{ number_format($loadAverage[2] ?? 0, 2) }}
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                <span>Memory Usage</span>
                                <span id="memory-usage-details" class="font-medium">{{ $memoryStats["used"] }}MB / {{ $memoryStats["total"] }}MB</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                <div id="memory-usage-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-out" style="width: {{ $memoryStats["percentage"] }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                <span>Database Status</span>
                            </div>
                            <div class="flex items-center">
                                <span id="db-status-indicator" class="h-3 w-3 rounded-full mr-2 flex-shrink-0 {{ $dbStatus === "Online" ? "bg-green-500" : "bg-red-500" }}"></span>
                                <span id="db-status-text" class="text-gray-900 dark:text-white font-semibold">{{ $dbStatus }}</span>
                            </div>
                            @if($dbError)
                                <div class="text-xs text-red-500 mt-1" title="{{ $dbError }}">Error: {{ Str::limit($dbError, 50) }}</div>
                            @endif
                        </div>
                         <div>
                            <div class="flex justify-between text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                <span>AI Agent Status</span> {{-- Placeholder --}}
                            </div>
                             <div class="flex items-center">
                                <span class="h-3 w-3 rounded-full mr-2 bg-gray-400 animate-pulse"></span>
                                <span class="text-gray-900 dark:text-white font-semibold">Monitoring...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Distribution & Recent Activity Widget -->
        <div class="widget" data-widget-id="user_activity">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- User Distribution Chart -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Distribution</h2>
                    <div class="h-64 md:h-72 flex items-center justify-center">
                        <canvas id="user-distribution-chart"></canvas>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-md rounded-lg p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activities</h2>
                        <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">View All</a> {{-- Link to full activity log --}}
                    </div>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700 max-h-72 overflow-y-auto">
                        @forelse($recentUsers->merge($recentLicenses)->sortByDesc("created_at") as $activity)
                            @if($activity instanceof \App\Models\User)
                                <li class="py-3 flex items-center">
                                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-full p-2 mr-3">
                                        <i class="fas fa-user-plus text-blue-600 dark:text-blue-300"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">New user <strong>{{ $activity->name }}</strong> registered</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </li>
                            @elseif($activity instanceof \App\Models\License)
                                <li class="py-3 flex items-center">
                                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-full p-2 mr-3">
                                        <i class="fas fa-credit-card text-green-600 dark:text-green-300"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">New license purchase from <strong>{{ $activity->user->name ?? "Unknown User" }}</strong></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </li>
                            @endif
                        @empty
                            <li class="py-3 text-center text-gray-500 dark:text-gray-400">No recent activity.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div> <!-- End Widgets Container -->

</div>
@endsection

@push("scripts")
{{-- Chart.js already included in app.blade.php --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const systemLoadEl = document.getElementById("system-load");
    const memoryUsageBarEl = document.getElementById("memory-usage-bar");
    const memoryUsageDetailsEl = document.getElementById("memory-usage-details");
    const dbStatusIndicatorEl = document.getElementById("db-status-indicator");
    const dbStatusTextEl = document.getElementById("db-status-text");

    let systemHealthInterval;

    function updateSystemHealth() {
        fetch("{{ route("admin.dashboard.system_health") }}")
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (systemLoadEl) {
                    systemLoadEl.textContent = `${data.load_1m} / ${data.load_5m} / ${data.load_15m}`;
                }
                if (memoryUsageBarEl && memoryUsageDetailsEl) {
                    const percentage = data.memory_percentage !== 'N/A' ? data.memory_percentage : 0;
                    memoryUsageBarEl.style.width = `${percentage}%`;
                    memoryUsageDetailsEl.textContent = data.memory_used !== 'N/A' ? `${data.memory_used}MB / ${data.memory_total}MB` : 'N/A';
                }
                if (dbStatusIndicatorEl && dbStatusTextEl) {
                    dbStatusTextEl.textContent = data.db_status;
                    const isOnline = data.db_status === "Online";
                    dbStatusIndicatorEl.classList.toggle("bg-green-500", isOnline);
                    dbStatusIndicatorEl.classList.toggle("bg-red-500", !isOnline);
                }
            })
            .catch(error => {
                console.error("Error fetching system health:", error);
                // Optionally display an error message on the dashboard
                if (systemLoadEl) systemLoadEl.textContent = 'Error';
                if (memoryUsageDetailsEl) memoryUsageDetailsEl.textContent = 'Error';
                if (dbStatusTextEl) dbStatusTextEl.textContent = 'Error';
                if (dbStatusIndicatorEl) {
                     dbStatusIndicatorEl.classList.remove("bg-green-500");
                     dbStatusIndicatorEl.classList.add("bg-red-500");
                }
                // Stop interval if fetch fails consistently?
                // clearInterval(systemHealthInterval);
            });
    }

    // Initial load
    updateSystemHealth();
    // Update every 10 seconds
    systemHealthInterval = setInterval(updateSystemHealth, 10000);

    // Revenue Chart
    const revenueCtx = document.getElementById("revenue-chart");
    if (revenueCtx) {
        fetch("{{ route("admin.dashboard.revenue") }}")
            .then(response => response.json())
            .then(data => {
                const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                const values = Array(12).fill(0);
                data.forEach(item => {
                    if (item.month >= 1 && item.month <= 12) {
                         values[item.month - 1] = item.total;
                    }
                });
                new Chart(revenueCtx, {
                    type: "line",
                    data: {
                        labels: months,
                        datasets: [{
                            label: "Revenue",
                            data: values,
                            borderColor: "#4C6FFF", // Blue
                            backgroundColor: "rgba(76, 111, 255, 0.1)",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: "#4C6FFF",
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: "rgba(128, 128, 128, 0.1)" }, // Lighter grid lines
                                ticks: { 
                                    callback: value => `$${value}`,
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#6b7280' // Adjust tick color for dark mode
                                 }
                            },
                            x: { 
                                grid: { display: false },
                                ticks: { color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#6b7280' }
                             }
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                         }
                    }
                });
            });
    }

    // User Distribution Chart
    const userDistCtx = document.getElementById("user-distribution-chart");
    if (userDistCtx) {
        // Placeholder data - replace with actual data fetch if available
        const userDistData = {
            labels: ["Free", "Basic", "Premium", "Enterprise"], // Example labels
            datasets: [{
                data: [{{ rand(20, 50) }}, {{ rand(10, 30) }}, {{ rand(5, 20) }}, {{ rand(1, 10) }}], // Example random data
                backgroundColor: ["#3DD598", "#4C6FFF", "#FFD166", "#FF6B6B"], // Green, Blue, Yellow, Red
                hoverOffset: 4,
                borderWidth: 0
            }]
        };

        new Chart(userDistCtx, {
            type: "doughnut",
            data: userDistData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: { 
                            padding: 20, 
                            boxWidth: 12,
                            color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#6b7280' // Adjust legend color for dark mode
                         }
                    }
                },
                cutout: "70%"
            }
        });
    }

    // Add logic for widget customization (e.g., using SortableJS) if needed

});
</script>
@endpush

