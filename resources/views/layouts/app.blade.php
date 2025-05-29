<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100 dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title", config("app.name", "Rankolab")) - Rankolab Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    {{-- Use asset() helper for CSS files - Ensure these files exist and contain appropriate styles --}}
    <link href="{{ asset('css/rankolab-admin.css') }}" rel="stylesheet">   {{-- <link href="{{ asset('css/admin-ui.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('css/enhanced-dashboard.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet"> --}}
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Favicon -->
    {{-- Ensure favicon.ico exists in the public directory --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    {{-- Livewire styles removed as package is not installed --}}
    @stack('styles')
    <style>
        /* Basic dark mode compatibility */
        .dark .dark\:bg-gray-800 { background-color: #1f2937; }
        .dark .dark\:bg-gray-900 { background-color: #111827; }
        .dark .dark\:text-white { color: #fff; }
        .dark .dark\:text-gray-300 { color: #d1d5db; }
        .dark .dark\:border-gray-700 { border-color: #374151; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full font-sans antialiased" 
      x-data="{
          darkMode: localStorage.getItem('darkMode') === 'true',
          sidebarOpen: window.innerWidth >= 1024, // Default open on larger screens
          init() {
              this.$watch('darkMode', val => localStorage.setItem('darkMode', val));
              if (localStorage.getItem('darkMode') === 'true') {
                  document.documentElement.classList.add('dark');
              }
              window.addEventListener('resize', () => {
                  if (window.innerWidth < 1024) {
                      this.sidebarOpen = false;
                  } else {
                      this.sidebarOpen = true;
                  }
              });
          },
          toggleDarkMode() {
              this.darkMode = !this.darkMode;
              document.documentElement.classList.toggle('dark', this.darkMode);
          },
          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
          }
      }"
      x-init="init()">
    
    <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            @include('partials.header')
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900 p-6">
                @yield('content')
            </main>
            
            <!-- Footer -->
            {{-- Optional: Include footer if needed, ensure it's styled appropriately --}}
            {{-- @include('partials.footer') --}}
        </div>
    </div>
    
    {{-- Livewire scripts removed as package is not installed --}}
    @stack('scripts')
    
</body>
</html>

