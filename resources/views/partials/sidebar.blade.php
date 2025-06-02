<!-- Sidebar -->
<aside class="sidebar fixed inset-y-0 left-0 w-64 transform md:translate-x-0 transition-transform duration-300 z-30" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="sidebar-header">
            <img src="{{ asset('images/rankolab-logo.png') }}" alt="Rankolab Logo" class="h-10">
        </div>
        
        <!-- Navigation -->
        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            @if(Route::has('admin.websites.index'))
            <a href="{{ route('admin.websites.index') }}" class="menu-item {{ request()->routeIs('admin.websites.*') ? 'active' : '' }}">
                <i class="fas fa-globe"></i>
                <span>Websites</span>
            </a>
            @endif
            
            @if(Route::has('admin.blogs.index'))
            <a href="{{ route('admin.blogs.index') }}" class="menu-item {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Content</span>
            </a>
            @endif
            
            <div class="menu-label">Management</div>
            
            @if(Auth::check() && (Auth::user()->role === 'super_admin' || (method_exists(Auth::user(), 'hasPermission') && Auth::user()->hasPermission('manage_users'))))
            @if(Route::has('admin.users.index'))
            <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            @endif
            @endif
            
            @if(Auth::check() && (Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin'))
            @if(Route::has('admin.settings.index'))
            <a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            @endif
            
            @if(Route::has('api.monitoring'))
            <a href="{{ route('api.monitoring') }}" class="menu-item {{ request()->routeIs('api.*') ? 'active' : '' }}">
                <i class="fas fa-plug"></i>
                <span>Integrations</span>
            </a>
            @endif
            @endif
            
            @if(Auth::check() && Auth::user()->role === 'super_admin')
            <div class="menu-label">Super Admin</div>
            
            @if(Route::has('super.agent'))
            <a href="{{ route('super.agent') }}" class="menu-item {{ request()->routeIs('super.agent') ? 'active' : '' }}">
                <i class="fas fa-robot"></i>
                <span>AI Super Agent</span>
            </a>
            @endif
            
            @if(Route::has('admin.delegation'))
            <a href="{{ route('admin.delegation') }}" class="menu-item {{ request()->routeIs('admin.delegation') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span>Delegation</span>
            </a>
            @endif
            
            @if(Route::has('admin.payments.index'))
            <a href="{{ route('admin.payments.index') }}" class="menu-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i>
                <span>Payment Management</span>
            </a>
            @endif
            
            @if(Route::has('api.monitoring'))
            <a href="{{ route('api.monitoring') }}" class="menu-item {{ request()->routeIs('api.monitoring') ? 'active' : '' }}">
                <i class="fas fa-heartbeat"></i>
                <span>API Monitor</span>
            </a>
            @endif
            @endif
            
            @if(Auth::check())
            <div class="menu-label">Marketing</div>
            
            @if(Route::has('user.affiliate'))
            <a href="{{ route('user.affiliate') }}" class="menu-item {{ request()->routeIs('user.affiliate*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i>
                <span>Affiliate</span>
            </a>
            @endif
            
            @if(Route::has('admin.aitools.index'))
            <a href="{{ route('admin.aitools.index') }}" class="menu-item {{ request()->routeIs('admin.aitools.*') ? 'active' : '' }}">
                <i class="fas fa-tools"></i>
                <span>AI Tools</span>
            </a>
            @endif
            @endif
            
            <div class="menu-label">Account</div>
            
            @if(Route::has('user.profile'))
            <a href="{{ route('user.profile') }}" class="menu-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i>
                <span>Profile</span>
            </a>
            @endif
            
            @if(Route::has('logout'))
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            @endif
        </nav>
        
        <!-- User Profile -->
        <div class="user-profile mt-auto p-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="user-avatar">
                    {{ substr(Auth::user()->name ?? 'User', 0, 2) }}
                </div>
                <div class="ml-3">
                    <p class="user-name">{{ Auth::user()->name ?? 'User' }}</p>
                    @if(Auth::check())
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(Auth::user()->role ?? 'User') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile sidebar backdrop -->
<div 
    x-show="sidebarOpen" 
    @click="sidebarOpen = false" 
    class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 md:hidden"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
></div>

