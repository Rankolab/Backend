@extends("layouts.app")

@section("title", "Edit User: " . $user->name)

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit User: <span class="font-bold">{{ $user->name }}</span></h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li><a href="{{ route("admin.users.index") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Users</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Edit User</li>
            </ol>
        </nav>
    </div>

    <!-- Session Messages -->
    @include("partials.session_messages")

    <!-- Edit User Form -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
        <form method="POST" action="{{ route("admin.users.update", $user->id) }}">
            @csrf
            @method("PUT")

            <!-- User Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" id="name" value="{{ old("name", $user->name) }}" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white @error("name") border-red-500 @enderror">
                @error("name")
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- User Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old("email", $user->email) }}" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white @error("email") border-red-500 @enderror">
                @error("email")
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- User Roles -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign Roles</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse ($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->id }}"
                                   {{ in_array($role->id, old("roles", $user->roles->pluck("id")->toArray())) ? "checked" : "" }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                            <label for="role_{{ $role->id }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                {{ $role->name }}
                            </label>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400 col-span-full">No roles available. Create roles first.</p>
                    @endforelse
                </div>
                 @error("roles")
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                 @error("roles.*")
                    <p class="mt-1 text-xs text-red-500">Invalid role selected.</p>
                @enderror
            </div>

            <!-- Password Section (Optional) -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password (Optional - leave blank to keep current)</label>
                <input type="password" name="password" id="password"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white @error("password") border-red-500 @enderror">
                @error("password")
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route("admin.users.index") }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Update User
                </button>
            </div>
        </form>
    </div>

    <!-- Linked Information Section -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Linked Information</h2>

        <!-- Linked Websites -->
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Websites</h3>
            @if($user->websites && $user->websites->count() > 0)
                <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 dark:text-gray-400">
                    @foreach($user->websites as $website)
                        <li>
                            <a href="{{ route("admin.websites.show", $website->id) }}" class="text-indigo-600 hover:underline">{{ $website->domain }}</a> (Status: {{ $website->status ?? "N/A" }})
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No websites linked to this user.</p>
            @endif
            {{-- Add link to manage/add websites if applicable --}}
        </div>

        <!-- Linked Licenses -->
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Licenses</h3>
             @if($user->licenses && $user->licenses->count() > 0)
                <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 dark:text-gray-400">
                    @foreach($user->licenses as $license)
                        <li>
                            <a href="{{ route("admin.licenses.show", $license->id) }}" class="text-indigo-600 hover:underline">Key: {{ Str::limit($license->key, 15, "...") }}</a>
                            (Type: {{ $license->type }}, Status: {{ $license->status }}, Expires: {{ $license->expires_at ? $license->expires_at->format("Y-m-d") : "N/A" }})
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No licenses linked to this user.</p>
            @endif
             {{-- Add link to manage/add licenses if applicable --}}
        </div>

        <!-- Linked Purchases/Payments -->
        <div>
            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Purchase History</h3>
             @if($user->purchases && $user->purchases->count() > 0)
                <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 dark:text-gray-400">
                    @foreach($user->purchases as $purchase)
                        <li>
                            <a href="{{ route("admin.purchases.show", $purchase->id) }}" class="text-indigo-600 hover:underline">ID: {{ $purchase->id }}</a>
                            (Amount: ${{ number_format($purchase->amount / 100, 2) }}, Status: {{ $purchase->status }}, Date: {{ $purchase->created_at->format("Y-m-d") }})
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No purchase history found for this user.</p>
            @endif
             {{-- Add link to view full payment history if applicable --}}
        </div>
    </div>

</div>
@endsection

