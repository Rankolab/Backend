@extends("layouts.app")

@section("title", "Manage API Keys")

@push("styles")
{{-- Add any specific styles for this page if needed --}}
@endpush

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">API Key Management</h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li><a href="{{ route("admin.api.monitoring") }}" class="hover:text-gray-700 dark:hover:text-gray-200">API</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Keys</li>
            </ol>
        </nav>
    </div>

    <!-- Session Messages -->
    @include("partials.session_messages")

    <!-- API Keys Form -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Update API Keys</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Update the API keys used by the system to interact with external services.</p>

        <form method="POST" action="{{ route("admin.api.keys.update") }}">
            @csrf
            @method("POST") {{-- Assuming a POST route for simplicity, adjust if needed --}}

            <div class="space-y-6">
                @forelse ($apiServices as $service)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <div class="md:col-span-1">
                            <label for="api_key_{{ $service->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $service->name }} Key</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Category: {{ $service->category }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <input type="password" name="api_keys[{{ $service->id }}]" id="api_key_{{ $service->id }}" 
                                   value="{{ old("api_keys.".$service->id, $service->api_key ? "********" : "") }}" 
                                   placeholder="Enter new API key for {{ $service->name }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                            {{-- Add a button/checkbox to reveal the key if needed --}}
                            @error("api_keys.".$service->id)
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No API services configured.</p>
                @endforelse
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Update Keys
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push("scripts")
{{-- Add any specific scripts for this page if needed (e.g., show/hide password) --}}
@endpush

