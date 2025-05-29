@extends("layouts.app")

@section("title", "Application Settings")

@push("styles")
{{-- Add any specific styles for this page if needed --}}
@endpush

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Application Settings</h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Settings</li>
            </ol>
        </nav>
    </div>

    <!-- Session Messages -->
    @include("partials.session_messages")

    <!-- Settings Form -->
    <form method="POST" action="{{ route("admin.settings.update") }}">
        @csrf
        @method("POST") {{-- Use POST, controller handles logic --}}

        <div class="space-y-8">
            @forelse ($settings as $group => $groupSettings)
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 capitalize">{{ str_replace("_", " ", $group) }} Settings</h2>
                    <div class="space-y-6">
                        @foreach ($groupSettings as $setting)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div class="md:col-span-1">
                                    <label for="setting_{{ $setting->key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ ucwords(str_replace("_", " ", $setting->key)) }}</label>
                                    @if($setting->description)
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $setting->description }}</p>
                                    @endif
                                </div>
                                <div class="md:col-span-2">
                                    @if($setting->type === "boolean")
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" value="true" 
                                                   class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                                   {{ old("settings.".$setting->key, $setting->value) == "true" ? "checked" : "" }}>
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Enable</span>
                                        </label>
                                    @elseif($setting->type === "textarea")
                                        <textarea name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" rows="3"
                                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">{{ old("settings.".$setting->key, $setting->value) }}</textarea>
                                    @elseif($setting->type === "integer")
                                         <input type="number" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" 
                                               value="{{ old("settings.".$setting->key, $setting->value) }}" 
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                                    @else {{-- Default to string input --}}
                                        <input type="text" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" 
                                               value="{{ old("settings.".$setting->key, $setting->value) }}" 
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                                    @endif
                                    @error("settings.".$setting->key)
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <p class="text-center text-gray-500 dark:text-gray-400">No settings found.</p>
                </div>
            @endforelse
        </div>

        <!-- Form Actions -->
        <div class="mt-8 flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                Save Settings
            </button>
        </div>
    </form>

</div>
@endsection

@push("scripts")
{{-- Add any specific scripts for this page if needed --}}
@endpush

