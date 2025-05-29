@extends("layouts.app")

@section("title", "Edit Website: " . $website->domain)

@push("styles")
{{-- Add any specific styles for this page if needed --}}
@endpush

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Website: <span class="font-bold">{{ $website->domain }}</span></h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li><a href="{{ route("admin.websites.index") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Websites</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Edit Website</li>
            </ol>
        </nav>
    </div>

    <!-- Validation Errors -->
    @include("partials.validation_errors")

    <!-- Edit Website Form -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form method="POST" action="{{ route("admin.websites.update", $website->website_id) }}">
            @csrf
            @method("PUT")

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Domain -->
                <div>
                    <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Domain URL <span class="text-red-500">*</span></label>
                    <input type="url" name="domain" id="domain" value="{{ old("domain", $website->domain) }}" required placeholder="https://example.com"
                           class="mt-1 block w-full px-3 py-2 border {{ $errors->has("domain") ? "border-red-500" : "border-gray-300 dark:border-gray-600" }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    @error("domain")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Website Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old("name", $website->name) }}" required placeholder="My Awesome Blog"
                           class="mt-1 block w-full px-3 py-2 border {{ $errors->has("name") ? "border-red-500" : "border-gray-300 dark:border-gray-600" }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    @error("name")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assigned User <span class="text-red-500">*</span></label>
                    <select name="user_id" id="user_id" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border {{ $errors->has("user_id") ? "border-red-500" : "border-gray-300 dark:border-gray-600" }} focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="" disabled>Select a user...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old("user_id", $website->user_id) == $user->id ? "selected" : "" }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error("user_id")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Niche -->
                <div>
                    <label for="niche" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Niche</label>
                    <input type="text" name="niche" id="niche" value="{{ old("niche", $website->niche) }}" placeholder="e.g., Technology, Health, Finance"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    @error("niche")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                 <!-- Website Type -->
                <div>
                    <label for="website_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website Type</label>
                    <input type="text" name="website_type" id="website_type" value="{{ old("website_type", $website->website_type) }}" placeholder="e.g., Blog, E-commerce, Portfolio"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    @error("website_type")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- License (Optional) -->
                <div class="md:col-span-2">
                    <label for="license_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assigned License (Optional)</label>
                    <select name="license_id" id="license_id"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="">No License</option>
                        @foreach($licenses as $license)
                            {{-- Check if license is available or already assigned to this website --}}
                            @php
                                $isAssignedToOther = $license->website && $license->website->website_id !== $website->website_id;
                            @endphp
                            <option value="{{ $license->id }}" 
                                    {{ old("license_id", $website->license_id) == $license->id ? "selected" : "" }} 
                                    {{ $isAssignedToOther ? "disabled" : "" }}>
                                {{ $license->key }} ({{ $license->status }}) {{ $isAssignedToOther ? "(Assigned to another site)" : "" }}
                            </option>
                        @endforeach
                    </select>
                     <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Select a license to assign to this website. Licenses marked as assigned cannot be selected.</p>
                    @error("license_id")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                 {{-- Add Status field if needed --}}
                 {{-- <div class="md:col-span-2">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" id="status" required class="mt-1 block w-full ...">
                        <option value="pending" {{ old("status", $website->status) == "pending" ? "selected" : "" }}>Pending</option>
                        <option value="active" {{ old("status", $website->status) == "active" ? "selected" : "" }}>Active</option>
                        <option value="inactive" {{ old("status", $website->status) == "inactive" ? "selected" : "" }}>Inactive</option>
                    </select>
                    @error("status") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div> --}}

            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route("admin.websites.index") }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Update Website
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push("scripts")
{{-- Add any specific scripts for this page if needed --}}
@endpush

