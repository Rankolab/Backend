@extends("layouts.app")

@section("title", "Activity Log Details #" . $activity->id)

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Activity Log Details</h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li><a href="{{ route("admin.activity_logs.index") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Activity Logs</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Details #{{ $activity->id }}</li>
            </ol>
        </nav>
    </div>

    <!-- Activity Details Card -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Log Entry #{{ $activity->id }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Details about the recorded activity.
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200 dark:sm:divide-gray-700">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Log Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $activity->log_name }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $activity->description }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Event</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $activity->event ?? "N/A" }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Subject</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        @if ($activity->subject)
                            {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                            {{-- Optionally link to the subject resource --}}
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Causer</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        @if ($activity->causer)
                             {{ $activity->causer->name ?? "System" }} (ID: {{ $activity->causer_id }}, Type: {{ class_basename($activity->causer_type) }})
                             {{-- Optionally link to the causer resource --}}
                        @else
                            System/Unknown
                        @endif
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Timestamp</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $activity->created_at->format("Y-m-d H:i:s T") }} ({{ $activity->created_at->diffForHumans() }})</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Properties (Changes)</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        @if ($activity->properties && $activity->properties->count() > 0)
                            <pre class="bg-gray-100 dark:bg-gray-900 p-3 rounded-md text-xs overflow-x-auto">{{ json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        @else
                            No specific properties recorded.
                        @endif
                    </dd>
                </div>
                 <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Batch UUID</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $activity->batch_uuid ?? "N/A" }}</dd>
                </div>
            </dl>
        </div>
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6 border-t border-gray-200 dark:border-gray-600">
             <a href="{{ route("admin.activity_logs.index") }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Logs
            </a>
        </div>
    </div>
</div>
@endsection

