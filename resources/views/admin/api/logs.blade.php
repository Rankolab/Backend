@extends("layouts.app")

@section("title", "API Call Logs")

@push("styles")
{{-- Add any specific styles for this page if needed --}}
@endpush

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">API Call Logs</h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li><a href="{{ route("admin.api.monitoring") }}" class="hover:text-gray-700 dark:hover:text-gray-200">API</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Logs</li>
            </ol>
        </nav>
    </div>

    <!-- Session Messages -->
    @include("partials.session_messages")

    <!-- API Logs Table -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Timestamp</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Endpoint</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status Code</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Response Time (ms)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User ID</th> {{-- Optional --}}
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">IP Address</th> {{-- Optional --}}
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($log->created_at)->format("Y-m-d H:i:s") }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $log->service_name ?? "Unknown" }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 truncate max-w-xs" title="{{ $log->endpoint }}">{{ $log->endpoint }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($log->status_code >= 500) bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                    @elseif($log->status_code >= 400) bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                    @elseif($log->status_code >= 300) bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                    @elseif($log->status_code >= 200) bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200 @endif">
                                    {{ $log->status_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-300">{{ number_format($log->response_time, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $log->user_id ?? "N/A" }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $log->ip_address ?? "N/A" }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                No API logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if ($logs->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push("scripts")
{{-- Add any specific scripts for this page if needed --}}
@endpush

