@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card">
            <span class="stat-label">Total Users</span>
            <span class="stat-value">{{ $totalUsers ?? '1,245' }}</span>
            <span class="text-sm text-green-500 flex items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                12% increase
            </span>
        </div>
        
        <div class="stat-card">
            <span class="stat-label">Monthly Revenue</span>
            <span class="stat-value">${{ $monthlyRevenue ?? '8,540' }}</span>
            <span class="text-sm text-green-500 flex items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                8.2% increase
            </span>
        </div>
        
        <div class="stat-card">
            <span class="stat-label">Active Licenses</span>
            <span class="stat-value">{{ $activeLicenses ?? '876' }}</span>
            <span class="text-sm text-green-500 flex items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                5.3% increase
            </span>
        </div>
        
        <div class="stat-card">
            <span class="stat-label">Conversion Rate</span>
            <span class="stat-value">{{ $conversionRate ?? '3.2%' }}</span>
            <span class="text-sm text-red-500 flex items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
                0.5% decrease
            </span>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="card lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue Overview</h3>
                <div class="flex space-x-2">
                    <button class="btn-secondary text-xs py-1">Monthly</button>
                    <button class="btn-secondary text-xs py-1">Quarterly</button>
                    <button class="btn-secondary text-xs py-1">Yearly</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="revenue-chart"></canvas>
            </div>
        </div>
        
        <!-- Traffic Sources -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Traffic Sources</h3>
            <div class="h-80">
                <canvas id="traffic-sources-chart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity and User Growth -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Growth -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Growth</h3>
            <div class="h-60">
                <canvas id="user-growth-chart"></canvas>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                <a href="#" class="text-sm text-primary-blue hover:underline">View All</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-green flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">New user registered</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">John Smith signed up for a premium account</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-blue flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">New payment received</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">$199.00 payment for Enterprise Plan</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">5 hours ago</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-500 flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">System Alert</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">API rate limit reached for Google Analytics</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">1 day ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- To-Do List -->
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">To-Do List</h3>
            <button class="btn-primary text-xs py-1">Add Task</button>
        </div>
        <div class="space-y-2" x-data="{ tasks: [
            { id: 1, text: 'Review new user signups', completed: false },
            { id: 2, text: 'Prepare monthly financial report', completed: false },
            { id: 3, text: 'Update API documentation', completed: true },
            { id: 4, text: 'Schedule team meeting', completed: true }
        ] }">
            <template x-for="task in tasks" :key="task.id">
                <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <input type="checkbox" :checked="task.completed" @click="task.completed = !task.completed" class="h-4 w-4 text-primary-green focus:ring-primary-green border-gray-300 rounded">
                    <span class="ml-3 text-sm" :class="{ 'line-through text-gray-500': task.completed }" x-text="task.text"></span>
                    <button class="ml-auto text-gray-500 hover:text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection
