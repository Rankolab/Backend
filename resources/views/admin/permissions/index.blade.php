@extends("layouts.app")

@section("title", "Manage Permissions")

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2 sm:mb-0">Permissions Management</h1>
        <a href="{{ route("admin.permissions.create") }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            <i class="fas fa-plus mr-2"></i> Create New Permission
        </a>
    </div>

    <!-- Session Messages -->
    @include("partials.session_messages")

    <!-- Permissions Table -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Permission Name</th>
                        {{-- Add columns for description or group if implemented --}}
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($permissions as $permission)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $loop->iteration + ($permissions->currentPage() - 1) * $permissions->perPage() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $permission->name }}</td>
                            {{-- Add cells for description or group --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                {{-- Edit link is usually disabled for permissions to avoid breaking checks --}}
                                <span class="text-gray-400 dark:text-gray-600 cursor-not-allowed" title="Editing permissions is discouraged">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <form method="POST" action="{{ route("admin.permissions.destroy", $permission->id) }}" class="inline-block" onsubmit="return confirm("Are you sure you want to delete this permission? This might affect user access.");">
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
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                No permissions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if ($permissions->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $permissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

