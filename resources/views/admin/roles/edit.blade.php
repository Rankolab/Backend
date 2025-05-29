@extends("layouts.app")

@section("title", "Edit Role: " . $role->name)

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Role: <span class="font-bold">{{ $role->name }}</span></h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li><a href="{{ route("admin.roles.index") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Roles</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Edit Role</li>
            </ol>
        </nav>
    </div>

    <!-- Session Messages -->
    @include("partials.session_messages")

    <!-- Edit Role Form -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form method="POST" action="{{ route("admin.roles.update", $role->id) }}">
            @csrf
            @method("PUT")

            <!-- Role Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role Name</label>
                <input type="text" name="name" id="name" value="{{ old("name", $role->name) }}" required
                       {{ in_array($role->name, ["admin", "superadmin", "user"]) ? "readonly" : "" }} {{-- Prevent editing core role names --}}
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white @error("name") border-red-500 @enderror {{ in_array($role->name, ["admin", "superadmin", "user"]) ? "bg-gray-100 dark:bg-gray-600 cursor-not-allowed" : "" }}">
                 @if(in_array($role->name, ["admin", "superadmin", "user"]))
                    <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">Core role names cannot be changed.</p>
                 @endif
                @error("name")
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Permissions -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign Permissions</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse ($permissions as $permission)
                        <div class="flex items-center">
                            <input type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}" value="{{ $permission->id }}"
                                   {{ $role->permissions->contains($permission->id) || in_array($permission->id, old("permissions", [])) ? "checked" : "" }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                            <label for="permission_{{ $permission->id }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                {{ $permission->name }}
                            </label>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400 col-span-full">No permissions available.</p>
                    @endforelse
                </div>
                 @error("permissions")
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                 @error("permissions.*")
                    <p class="mt-1 text-xs text-red-500">Invalid permission selected.</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route("admin.roles.index") }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Update Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

