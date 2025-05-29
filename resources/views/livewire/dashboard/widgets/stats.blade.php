<div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow" wire:key="stats-widget">
    <h3 class="text-lg font-semibold mb-4">Stats</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="stat-card bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-800/50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            @if($userGrowth > 0)
                <div class="mt-2 text-sm text-green-600 dark:text-green-400">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        {{ number_format($userGrowth, 1) }}% from last month
                    </span>
                </div>
            @elseif($userGrowth < 0)
                <div class="mt-2 text-sm text-red-600 dark:text-red-400">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                        {{ number_format(abs($userGrowth), 1) }}% from last month
                    </span>
                </div>
            @else
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    No change from last month
                </div>
            @endif
        </div>
        
        <div class="stat-card bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($revenue, 2) }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-800/50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            @if($revenueGrowth > 0)
                <div class="mt-2 text-sm text-green-600 dark:text-green-400">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        {{ number_format($revenueGrowth, 1) }}% from last month
                    </span>
                </div>
            @elseif($revenueGrowth < 0)
                <div class="mt-2 text-sm text-red-600 dark:text-red-400">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                        {{ number_format(abs($revenueGrowth), 1) }}% from last month
                    </span>
                </div>
            @else
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    No change from last month
                </div>
            @endif
        </div>
    </div>
</div>
