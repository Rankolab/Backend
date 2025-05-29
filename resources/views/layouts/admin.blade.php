<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title", "Rankolab Admin")</title>
    
    <!-- SEO Meta Tags (Admin Panel - No Index) -->
    <meta name="description" content="Rankolab Administration Panel - Manage users, licenses, content, and system settings.">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset("favicon.ico") }}">
    
    @livewireStyles
    
    <style>
        body {
            font-family: "Inter", sans-serif;
        }
        
        .sidebar-link {
            @apply flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors;
            @apply text-gray-600 hover:bg-gray-100 hover:text-gray-900;
            @apply dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white;
        }
        
        .sidebar-link.active {
            @apply bg-primary-green bg-opacity-10 text-primary-green;
            @apply dark:bg-primary-green dark:bg-opacity-20 dark:text-primary-green;
        }
        
        .stat-card {
            @apply bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6;
        }
        
        .stat-label {
            @apply block text-sm font-medium text-gray-500 dark:text-gray-400;
        }
        
        .stat-value {
            @apply block text-2xl font-semibold text-gray-900 dark:text-white mt-1;
        }
        
        .card {
            @apply bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6;
        }
        
        .btn-primary {
            @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-green hover:bg-primary-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-green;
        }
        
        .btn-secondary {
            @apply inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-blue;
        }
        
        :root {
            --color-primary-green: #3DD598;
            --color-primary-green-dark: #2BB583;
            --color-primary-blue: #4C6FFF;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100 dark:bg-gray-900 font-inter antialiased" 
      x-data="{ 
          darkMode: localStorage.getItem("darkMode") === "true",
          sidebarOpen: false,
          toggleDarkMode() {
              this.darkMode = !this.darkMode;
              localStorage.setItem("darkMode", this.darkMode);
              document.documentElement.classList.toggle("dark", this.darkMode);
          },
          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
          }
      }"
      x-init="document.documentElement.classList.toggle("dark", darkMode)">
    
    <!-- Sidebar -->
    @include("partials.sidebar")
    
    <!-- Main Content -->
    <div class="md:pl-64 flex flex-col min-h-screen">
        <!-- Top Navigation -->
        @include("partials.topnav")
        
        <!-- Page Content -->
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
            @yield("content")
        </main>
        
        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 shadow-inner py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ date("Y") }} Rankolab. All rights reserved.
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-primary-green dark:hover:text-primary-green">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-primary-green dark:hover:text-primary-green">
                        <span class="sr-only">GitHub</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </footer>
    </div>
    
    @livewireScripts
    
    <script>
        // Initialize charts when the DOM is loaded
        document.addEventListener("DOMContentLoaded", function() {
            // Revenue Chart
            const revenueCtx = document.getElementById("revenue-chart");
            if (revenueCtx) {
                new Chart(revenueCtx, {
                    type: "line",
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                            label: "Revenue",
                            data: [5000, 7500, 6500, 8000, 9500, 11000, 10500, 12000, 12500, 13000, 14500, 15000],
                            borderColor: "#3DD598",
                            backgroundColor: "rgba(61, 213, 152, 0.1)",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: "rgba(0, 0, 0, 0.05)"
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
            
            // Traffic Sources Chart
            const trafficCtx = document.getElementById("traffic-sources-chart");
            if (trafficCtx) {
                new Chart(trafficCtx, {
                    type: "doughnut",
                    data: {
                        labels: ["Organic Search", "Direct", "Referral", "Social Media"],
                        datasets: [{
                            data: [45, 25, 20, 10],
                            backgroundColor: ["#3DD598", "#4C6FFF", "#FF6B6B", "#FFD166"],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: "bottom"
                            }
                        },
                        cutout: "70%"
                    }
                });
            }
            
            // User Growth Chart
            const userGrowthCtx = document.getElementById("user-growth-chart");
            if (userGrowthCtx) {
                new Chart(userGrowthCtx, {
                    type: "bar",
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                        datasets: [{
                            label: "New Users",
                            data: [65, 85, 110, 125, 150, 180],
                            backgroundColor: "#4C6FFF",
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: "rgba(0, 0, 0, 0.05)"
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>

