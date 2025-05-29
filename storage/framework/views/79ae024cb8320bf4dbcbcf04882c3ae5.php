<?php $__env->startSection("title", "Analytics Dashboard"); ?>

<?php $__env->startPush("styles"); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Analytics Overview</h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="<?php echo e(route("admin.dashboard")); ?>" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Analytics</li>
            </ol>
        </nav>
    </div>

    <!-- Session Messages -->
    <?php echo $__env->make("partials.session_messages", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Analytics Widgets/Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Widget 1: Total Users -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white"><?php echo e(number_format($totalUsers)); ?></p>
            
        </div>

        <!-- Widget 2: Total Websites -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Websites</h3>
            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white"><?php echo e(number_format($totalWebsites)); ?></p>
        </div>

        <!-- Widget 3: Active Licenses -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Licenses</h3>
            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white"><?php echo e(number_format($activeLicenses)); ?></p>
        </div>

        <!-- Widget 4: API Calls (Last 24h) -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">API Calls (24h)</h3>
            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white"><?php echo e(number_format($apiCallsLast24h)); ?></p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Chart 1: User Registrations Over Time -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">User Registrations (Last 30 Days)</h3>
            <div class="h-64 md:h-72">
                 <canvas id="userRegistrationsChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Website Growth Over Time -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Website Growth (Last 30 Days)</h3>
             <div class="h-64 md:h-72">
                <canvas id="websiteGrowthChart"></canvas>
            </div>
        </div>
    </div>

     <!-- Detailed Reports Links -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detailed Reports</h3>
        <ul class="list-disc list-inside space-y-2 text-sm">
            <li><a href="<?php echo e(route("admin.users.index")); ?>" class="text-indigo-600 hover:underline dark:text-indigo-400">User Management Report</a></li>
            <li><a href="<?php echo e(route("admin.websites.index")); ?>" class="text-indigo-600 hover:underline dark:text-indigo-400">Website Activity Report</a></li>
            <li><a href="<?php echo e(route("admin.licenses.index")); ?>" class="text-indigo-600 hover:underline dark:text-indigo-400">License Usage Report</a></li>
            <li><a href="<?php echo e(route("admin.api.logs")); ?>" class="text-indigo-600 hover:underline dark:text-indigo-400">API Call Log Details</a></li>
            <li><a href="<?php echo e(route("admin.api.analytics")); ?>" class="text-indigo-600 hover:underline dark:text-indigo-400">API Performance Analytics</a></li>
            
        </ul>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const chartData = <?php echo json_encode($chartData, 15, 512) ?>;
    const isDarkMode = document.documentElement.classList.contains("dark");
    const gridColor = isDarkMode ? "rgba(107, 114, 128, 0.5)" : "rgba(209, 213, 219, 0.5)"; // gray-500/gray-300 with opacity
    const labelColor = isDarkMode ? "#d1d5db" : "#374151"; // gray-300/gray-700

    // User Registrations Chart
    const userRegCtx = document.getElementById("userRegistrationsChart");
    if (userRegCtx && chartData) {
        new Chart(userRegCtx, {
            type: "line",
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: "New Users",
                    data: chartData.userRegistrations,
                    borderColor: "rgb(79, 70, 229)", // indigo-600
                    backgroundColor: "rgba(79, 70, 229, 0.1)",
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: labelColor,
                            precision: 0 // Ensure whole numbers for counts
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                         ticks: {
                            color: labelColor,
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 10 // Adjust for readability
                        },
                        grid: {
                            color: gridColor
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Hide legend if only one dataset
                    }
                }
            }
        });
    }

    // Website Growth Chart
    const websiteGrowthCtx = document.getElementById("websiteGrowthChart");
    if (websiteGrowthCtx && chartData) {
        new Chart(websiteGrowthCtx, {
            type: "line",
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: "New Websites",
                    data: chartData.websiteGrowth,
                    borderColor: "rgb(5, 150, 105)", // emerald-600
                    backgroundColor: "rgba(5, 150, 105, 0.1)",
                    tension: 0.1,
                    fill: true
                }]
            },
             options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: labelColor,
                            precision: 0
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                         ticks: {
                            color: labelColor,
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 10
                        },
                        grid: {
                            color: gridColor
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
<?php $__env->stopPush(); ?>


<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/rankolab_backend/resources/views/admin/analytics/index.blade.php ENDPATH**/ ?>