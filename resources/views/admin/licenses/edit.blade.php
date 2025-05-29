@extends("layouts.app")

@section("title", "Edit License: " . $license->key)

@push("styles")
{{-- Add any specific styles for this page if needed --}}
@endpush

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit License</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Key: <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">{{ $license->key }}</code></p>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li><a href="{{ route("admin.licenses.index") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Licenses</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Edit License</li>
            </ol>
        </nav>
    </div>

    <!-- Validation Errors -->
    @include("partials.validation_errors")

    <!-- Edit License Form -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form method="POST" action="{{ route("admin.licenses.update", $license->id) }}">
            @csrf
            @method("PUT")

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- License Key (Readonly) -->
                <div>
                    <label for="key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">License Key</label>
                    <input type="text" name="key" id="key" value="{{ $license->key }}" readonly
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                </div>

                <!-- User Assignment -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assigned User (Optional)</label>
                    <select name="user_id" id="user_id"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="">-- No User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old("user_id", $license->user_id) == $user->id ? "selected" : "" }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error("user_id")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- License Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">License Type <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border {{ $errors->has("type") ? "border-red-500" : "border-gray-300 dark:border-gray-600" }} focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                        {{-- Define your license types here --}}
                        <option value="standard" {{ old("type", $license->type) == "standard" ? "selected" : "" }}>Standard</option>
                        <option value="premium" {{ old("type", $license->type) == "premium" ? "selected" : "" }}>Premium</option>
                        <option value="lifetime" {{ old("type", $license->type) == "lifetime" ? "selected" : "" }}>Lifetime</option>
                        <option value="trial" {{ old("type", $license->type) == "trial" ? "selected" : "" }}>Trial</option>
                    </select>
                    @error("type")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border {{ $errors->has("status") ? "border-red-500" : "border-gray-300 dark:border-gray-600" }} focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="active" {{ old("status", $license->status) == "active" ? "selected" : "" }}>Active</option>
                        <option value="inactive" {{ old("status", $license->status) == "inactive" ? "selected" : "" }}>Inactive</option>
                        <option value="pending" {{ old("status", $license->status) == "pending" ? "selected" : "" }}>Pending</option>
                        <option value="expired" {{ old("status", $license->status) == "expired" ? "selected" : "" }}>Expired</option>
                    </select>
                    @error("status")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expiration Date -->
                <div class="md:col-span-1">
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expiration Date (Optional)</label>
                    <input type="date" name="expires_at" id="expires_at" value="{{ old("expires_at", $license->expires_at ? $license->expires_at->format("Y-m-d") : null) }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Leave blank for non-expiring licenses.</p>
                    @error("expires_at")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Allowed Domains -->
                 <div class="md:col-span-1">
                    <label for="allowed_domains" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Allowed Domains</label>
                    <input type="number" name="allowed_domains" id="allowed_domains" value="{{ old("allowed_domains", $license->allowed_domains ?? 1) }}" min="1"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Number of domains this license can be activated on.</p>
                    @error("allowed_domains")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route("admin.licenses.index") }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Update License
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push("scripts")
{{-- Add any specific scripts for this page if needed --}}
@endpush

