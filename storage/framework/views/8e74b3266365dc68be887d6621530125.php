<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Performance Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-4 mb-md-0">
                            <div class="stat-card bg-gradient-primary text-white h-100">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5>Total Users</h5>
                                        <h2><?php echo e($stats['totalUsers'] ?? 0); ?></h2>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="text-white stretched-link" href="<?php echo e(route('admin.users.index')); ?>">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 mb-md-0">
                            <div class="stat-card bg-gradient-success text-white h-100">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5>Websites</h5>
                                        <h2><?php echo e($stats['totalWebsites'] ?? 0); ?></h2>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-globe"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="text-white stretched-link" href="<?php echo e(route('admin.websites.index')); ?>">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 mb-md-0">
                            <div class="stat-card bg-gradient-info text-white h-100">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5>Published Articles</h5>
                                        <h2><?php echo e($stats['publishedArticles'] ?? 0); ?></h2>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="text-white stretched-link" href="<?php echo e(route("admin.blogs.index")); ?>"iew Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-gradient-warning text-white h-100">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5>Active Licenses</h5>
                                        <h2><?php echo e($stats['activeLicenses'] ?? 0); ?></h2>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="text-white stretched-link" href="<?php echo e(route('admin.licenses.index')); ?>">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Revenue Trends
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    User Distribution
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list me-1"></i>
                    Recent Activities
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Activity</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = range(1, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>User <?php echo e($i); ?></td>
                                    <td><?php echo e(['Login', 'Content Update', 'License Purchase', 'Profile Update', 'Website Added'][$i-1]); ?></td>
                                    <td><?php echo e(now()->subHours($i)->format('H:i')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer small text-muted">
                    <a href="#" class="text-decoration-none">View all activities</a>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tasks me-1"></i>
                    System Health
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Server Load</h6>
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <h6 class="mb-3">Memory Usage</h6>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <h6 class="mb-3">Database</h6>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <h6 class="mb-3">API Health</h6>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="card-footer small text-muted">
                    <a href="#" class="text-decoration-none">View detailed report</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [12000, 19000, 15000, 25000, 22000, 30000, 35000, 32000, 40000, 38000, 45000, 50000],
                    backgroundColor: 'rgba(76, 212, 169, 0.2)',
                    borderColor: 'rgba(76, 212, 169, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(76, 212, 169, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        
        // User Distribution Chart
        const userDistributionCtx = document.getElementById('userDistributionChart').getContext('2d');
        const userDistributionChart = new Chart(userDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Free', 'Basic', 'Premium', 'Enterprise'],
                datasets: [{
                    data: [30, 40, 20, 10],
                    backgroundColor: [
                        'rgba(59, 133, 199, 0.8)',
                        'rgba(76, 212, 169, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(59, 133, 199, 1)',
                        'rgba(76, 212, 169, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/rankolab_backend/resources/views/admin/dashboard/index.blade.php ENDPATH**/ ?>