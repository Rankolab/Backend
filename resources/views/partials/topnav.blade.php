<!-- Top Navigation -->
<header class="sticky top-0 z-20 bg-white dark:bg-gray-800 shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Mobile menu button -->
            <button @click="toggleSidebar" type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            
            <!-- Page Title -->
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white md:block hidden">
                @yield('title', 'Dashboard')
            </h1>
            
            <!-- Right side buttons -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-1 rounded-full text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                        <span class="sr-only">View notifications</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                    </button>
                    
                    <!-- Notification dropdown -->
                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95">
                        <div class="py-1">
                            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notifications</h3>
                            </div>
                            <div class="max-h-60 overflow-y-auto">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <span class="inline-block h-2 w-2 rounded-full bg-green-500 mt-1"></span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium">New subscription</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">A new user subscribed to the premium plan</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <span class="inline-block h-2 w-2 rounded-full bg-blue-500 mt-1"></span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium">API Status Update</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">All systems operational</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">1 day ago</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700">
                                <a href="#" class="block px-4 py-2 text-sm text-center text-blue-500 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    View all notifications
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dark mode toggle -->
                <button @click="toggleDarkMode" class="p-1 rounded-full text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                    <span class="sr-only">Toggle dark mode</span>
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>
