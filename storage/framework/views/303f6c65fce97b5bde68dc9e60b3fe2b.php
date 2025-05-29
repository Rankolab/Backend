<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title'); ?> - Rankolab Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/rankolab-admin.css')); ?>">
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <img src="<?php echo e(asset('images/rankolab-logo.png')); ?>" alt="Rankolab Logo" class="img-fluid" style="max-width: 150px;">
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('admin/dashboard*') ? 'active' : ''); ?>" href="<?php echo e(url('/admin/dashboard')); ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('admin/users*') ? 'active' : ''); ?>" href="<?php echo e(url('/admin/users')); ?>">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('admin/websites*') ? 'active' : ''); ?>" href="<?php echo e(url('/admin/websites')); ?>">
                                <i class="fas fa-globe"></i> Websites
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('admin/content*') ? 'active' : ''); ?>" href="<?php echo e(url('/admin/content')); ?>">
                                <i class="fas fa-file-alt"></i> Content
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('admin/licenses*') ? 'active' : ''); ?>" href="<?php echo e(url('/admin/licenses')); ?>">
                                <i class="fas fa-key"></i> Licenses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('admin/api*') ? 'active' : ''); ?>" href="<?php echo e(url('/admin/api')); ?>">
                                <i class="fas fa-code"></i> API
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('admin/analytics*') ? 'active' : ''); ?>" href="<?php echo e(url('/admin/analytics')); ?>">
                                <i class="fas fa-chart-line"></i> Analytics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('admin/settings*') ? 'active' : ''); ?>" href="<?php echo e(url('/admin/settings')); ?>">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                        <?php if(auth()->user() && auth()->user()->role === 'superadmin'): ?>
                        <li class="nav-item mt-4">
                            <div class="nav-link text-white-50">
                                <i class="fas fa-robot"></i> AI Agent
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('admin/ai-agent') ? 'active' : ''); ?>" href="<?php echo e(url('/admin/ai-agent')); ?>">
                                <i class="fas fa-brain"></i> AI Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('admin/ai-agent/analytics*') ? 'active' : ''); ?>" href="<?php echo e(url('/admin/ai-agent/analytics')); ?>">
                                <i class="fas fa-chart-pie"></i> Predictive Analytics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('admin/ai-agent/security*') ? 'active' : ''); ?>" href="<?php echo e(url('/admin/ai-agent/security')); ?>">
                                <i class="fas fa-shield-alt"></i> Security Audit
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="d-flex align-items-center">
                            <span class="h5 mb-0"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></span>
                        </div>
                        <div class="navbar-nav">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> <?php echo e(auth()->user()->name ?? 'User'); ?>

                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="<?php echo e(url('/admin/profile')); ?>"><i class="fas fa-user me-2"></i> Profile</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(url('/admin/settings')); ?>"><i class="fas fa-cog me-2"></i> Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/ubuntu/rankolab_backend/resources/views/admin/layouts/app.blade.php ENDPATH**/ ?>