@extends("layouts.app")

@section("title", "Manage Websites")

@push("styles")
{{-- Add any specific styles for this page if needed --}}
@endpush

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Websites Management</h1>
            <nav class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex space-x-2">
                    <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                    <li><span>&gt;</span></li>
                    <li class="text-gray-700 dark:text-gray-200" aria-current="page">Websites</li>
                </ol>
            </nav>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route("admin.websites.create") }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-1"></i> Add Website
            </a>
        </div>
    </div>

    <!-- Session Messages -->
    @include("partials.session_messages")

    <!-- Filters and Search (Optional - Add similar to Users if needed) -->
    {{-- <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route("admin.websites.index") }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search Domain/User</label>
                    <input type="text" name="search" id="search" value="{{ request("search") }}" placeholder="Domain, User Name/Email..."
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="... btn-indigo ...">Filter</button>
                    <a href="{{ route("admin.websites.index") }}" class="... btn-secondary ...">Clear</a>
                </div>
            </div>
        </form>
    </div> --}}

    <!-- Websites Table -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Domain</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">License</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Niche</th>
                        {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th> --}}
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($websites as $website)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                <a href="{{ $website->domain }}" target="_blank" class="hover:text-indigo-600">{{ $website->domain }} <i class="fas fa-external-link-alt text-xs ml-1"></i></a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                @if($website->user)
                                    <a href="{{ route("admin.users.show", $website->user->id) }}" class="hover:text-indigo-600">{{ $website->user->name }}</a>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                @if($website->license)
                                    <a href="{{ route("admin.licenses.show", $website->license->id) }}" class="hover:text-indigo-600">{{ Str::limit($website->license->key, 15, "...") }}</a>
                                @else
                                    <span class="text-gray-400">None</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $website->niche ?? "-" }}</td>
                            {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $website->status === "active" ? "bg-green-100 text-green-800" : "bg-gray-100 text-gray-800" }}">
                                    {{ ucfirst($website->status ?? "Unknown") }}
                                </span>
                            </td> --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $website->created_at->format("Y-m-d") }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                <a href="{{ route("admin.websites.show", $website->website_id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route("admin.websites.edit", $website->website_id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route("admin.websites.destroy", $website->website_id) }}" class="inline-block" onsubmit="return confirm("Are you sure you want to delete this website? This action cannot be undone.");">
                                    @csrf
                                    @method("DELETE")
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                No websites found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if ($websites->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $websites->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push("scripts")
{{-- Add any specific scripts for this page if needed --}}
@endpush

