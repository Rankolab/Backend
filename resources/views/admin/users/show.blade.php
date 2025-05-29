@extends("layouts.app")

@section("title", "User Details: " . $user->name)

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">User Details: <span class="font-bold">{{ $user->name }}</span></h1>
            <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex space-x-2">
                    <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                    <li><span>&gt;</span></li>
                    <li><a href="{{ route("admin.users.index") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Users</a></li>
                    <li><span>&gt;</span></li>
                    <li class="text-gray-700 dark:text-gray-200" aria-current="page">User Details</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route("admin.users.edit", $user->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                <i class="fas fa-edit mr-1"></i> Edit User
            </a>
            <a href="{{ route("admin.users.index") }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Users List
            </a>
        </div>
    </div>

    <!-- Session Messages -->
    @include("partials.session_messages")

    <!-- User Details Card -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">User Information</h2>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->name }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->email }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Joined Date</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->created_at->format("F j, Y, g:i a") }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->updated_at->format("F j, Y, g:i a") }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Roles</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    @forelse ($user->roles as $role)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100 mr-1">{{ $role->name }}</span>
                    @empty
                        <span class="text-xs text-gray-400">No roles assigned</span>
                    @endforelse
                </dd>
            </div>
            {{-- Add other relevant user fields here if needed --}}
        </dl>
    </div>

    <!-- Linked Information Section -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
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
        </div>
    </div>

    <!-- Activity Log Section -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h2>
        @if($user->activity && $user->activity->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Timestamp</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($user->activity->take(10) as $log) {{-- Show latest 10 activities --}}
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $log->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $log->created_at->format("Y-m-d H:i:s") }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $log->properties->get("ip") ?? "N/A" }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($user->activity->count() > 10)
                <div class="mt-4 text-sm">
                    <a href="#" class="text-indigo-600 hover:underline">View All Activity</a> {{-- Link to a full activity log page if needed --}}
                </div>
            @endif
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400">No recent activity recorded for this user.</p>
        @endif
    </div>

</div>
@endsection

