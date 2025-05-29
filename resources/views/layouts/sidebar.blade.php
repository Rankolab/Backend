<aside 
    class="sidebar fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 ease-in-out transform lg:translate-x-0 lg:static lg:inset-0"
    :class="{ 
        
        '-translate-x-full': !sidebarOpen, 
        'translate-x-0': sidebarOpen 
    }"
    x-show="sidebarOpen || window.innerWidth >= 1024"
    @click.away="window.innerWidth < 1024 ? sidebarOpen = false : null"
    x-cloak
>
    <!-- Sidebar Header -->
    <div class="flex items-center justify-center h-16 px-6 bg-white dark:bg-gray-800 border-b dark:border-gray-700">
        <a href="{{ route("admin.dashboard") }}" class="text-xl font-semibold text-gray-700 dark:text-white">
            {{-- Replace with Logo if available --}}
            Rankolab
        </a>
    </div>

    <!-- Sidebar Links -->
    <nav class="mt-4 px-4">
        <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.dashboard") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
           href="{{ route("admin.dashboard") }}">
            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i> Dashboard
        </a>

        @if(auth()->check() && auth()->user()->hasPermission("manage_users"))
        <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.users.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
           href="{{ route("admin.users.index") }}">
            <i class="fas fa-users w-5 h-5 mr-3"></i> Users
        </a>
        @endif

        <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.websites.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
           href="{{ route("admin.websites.index") }}">
            <i class="fas fa-globe w-5 h-5 mr-3"></i> Websites
        </a>

        <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.blogs.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
           href="{{ route("admin.blogs.index") }}">
            <i class="fas fa-blog w-5 h-5 mr-3"></i> Content
        </a>

        @if(auth()->check() && auth()->user()->hasPermission("manage_licenses"))
        <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.licenses.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
           href="{{ route("admin.licenses.index") }}">
            <i class="fas fa-id-card w-5 h-5 mr-3"></i> Licenses
        </a>
        @endif

        {{-- Payment Management Section --}}
        <div class="mt-4">
            <span class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Payments</span>
            <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.plans.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
               href="{{ route("admin.plans.index") }}">
                <i class="fas fa-clipboard-list w-5 h-5 mr-3"></i> Plans
            </a>
            <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.subscriptions.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
               href="{{ route("admin.subscriptions.index") }}">
                <i class="fas fa-sync-alt w-5 h-5 mr-3"></i> Subscriptions
            </a>
            <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.payments.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
               href="{{ route("admin.payments.index") }}">
                <i class="fas fa-credit-card w-5 h-5 mr-3"></i> Payments
            </a>
        </div>

        {{-- Affiliate Management Section --}}
        <div class="mt-4">
            <span class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Affiliates</span>
            <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.affiliates.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
               href="{{ route("admin.affiliates.index") }}">
                <i class="fas fa-users-cog w-5 h-5 mr-3"></i> Affiliates
            </a>
            <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.commissions.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
               href="{{ route("admin.commissions.index") }}">
                <i class="fas fa-hand-holding-usd w-5 h-5 mr-3"></i> Commissions
            </a>
            <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.referrals.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
               href="{{ route("admin.referrals.index") }}">
                <i class="fas fa-link w-5 h-5 mr-3"></i> Referrals
            </a>
        </div>

        {{-- System Section --}}
        <div class="mt-4">
             <span class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">System</span>
            <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.api.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
               href="{{ route("admin.api.keys") }}"> {{-- Assuming keys is the main API page --}}
                <i class="fas fa-code w-5 h-5 mr-3"></i> API
            </a>

            <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.analytics.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
               href="{{ route("admin.analytics.index") }}">
                <i class="fas fa-chart-line w-5 h-5 mr-3"></i> Analytics
            </a>

            <a class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs("admin.settings.*") ? "bg-gray-200 dark:bg-gray-700" : "" }}" 
               href="{{ route("admin.settings.index") }}">
                <i class="fas fa-cog w-5 h-5 mr-3"></i> Settings
            </a>
        </div>

    </nav>
</aside>

