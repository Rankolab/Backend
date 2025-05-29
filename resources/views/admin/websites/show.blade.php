@extends("layouts.app")

@section("title", "Website Details: " . $website->domain)

@push("styles")
{{-- Add any specific styles for this page if needed --}}
@endpush

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Website Details: <a href="{{ $website->domain }}" target="_blank" class="font-bold hover:underline">{{ $website->domain }} <i class="fas fa-external-link-alt text-xs ml-1"></i></a></h1>
            <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex space-x-2">
                    <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                    <li><span>&gt;</span></li>
                    <li><a href="{{ route("admin.websites.index") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Websites</a></li>
                    <li><span>&gt;</span></li>
                    <li class="text-gray-700 dark:text-gray-200" aria-current="page">Website Details</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route("admin.websites.edit", $website->website_id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                <i class="fas fa-edit mr-1"></i> Edit Website
            </a>
            <a href="{{ route("admin.websites.index") }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Websites List
            </a>
        </div>
    </div>

    <!-- Session Messages -->
    @include("partials.session_messages")

    <!-- Website Details Card -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Website Information</h2>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Domain</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $website->domain }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Assigned User</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    @if($website->user)
                        <a href="{{ route("admin.users.show", $website->user->id) }}" class="text-indigo-600 hover:underline">{{ $website->user->name }}</a>
                    @else
                        <span class="text-gray-400">N/A</span>
                    @endif
                </dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Niche</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $website->niche ?? "Not specified" }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Website Type</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $website->website_type ?? "Not specified" }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Assigned License</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    @if($website->license)
                        <a href="{{ route("admin.licenses.show", $website->license->id) }}" class="text-indigo-600 hover:underline">{{ $website->license->key }}</a> (Status: {{ $website->license->status }})
                    @else
                        <span class="text-gray-400">None</span>
                    @endif
                </dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date Added</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $website->created_at->format("F j, Y, g:i a") }}</dd>
            </div>
             {{-- <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $website->status === "active" ? "bg-green-100 text-green-800" : "bg-gray-100 text-gray-800" }}">
                        {{ ucfirst($website->status ?? "Unknown") }}
                    </span>
                </dd>
            </div> --}}
        </dl>
    </div>

    <!-- Website Metrics/Stats Section (Example) -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Metrics & Statistics</h2>
        @if($website->metrics && $website->metrics->count() > 0)
            {{-- Display metrics - This is just a placeholder --}}
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                 <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Visitors (Last 30d)</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $website->metrics->visitors_last_30d ?? "N/A" }}</dd>
                </div>
                 <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Page Views (Last 30d)</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $website->metrics->page_views_last_30d ?? "N/A" }}</dd>
                </div>
                 <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Uptime (Last 24h)</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $website->metrics->uptime_last_24h ?? "N/A" }}%</dd>
                </div>
            </dl>
            <div class="mt-4 text-sm">
                <a href="#" class="text-indigo-600 hover:underline">View Detailed Analytics</a> {{-- Link to full analytics page --}}
            </div>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400">No metrics data available for this website yet.</p>
        @endif
    </div>

    <!-- Related Activity Section (Example) -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Related Activity</h2>
        {{-- Placeholder for activity logs related to this website --}}
        <p class="text-sm text-gray-500 dark:text-gray-400">Activity log specific to this website is not yet implemented.</p>
        {{-- @if($website->activity && $website->activity->count() > 0)
            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 dark:text-gray-400">
                @foreach($website->activity->take(5) as $log)
                    <li>{{ $log->description }} at {{ $log->created_at->format("Y-m-d H:i") }}</li>
                @endforeach
            </ul>
             @if($website->activity->count() > 5)
                <div class="mt-4 text-sm">
                    <a href="#" class="text-indigo-600 hover:underline">View All Website Activity</a>
                </div>
            @endif
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400">No recent activity recorded for this website.</p>
        @endif --}}
    </div>

</div>
@endsection

@push("scripts")
{{-- Add any specific scripts for this page if needed --}}
@endpush

