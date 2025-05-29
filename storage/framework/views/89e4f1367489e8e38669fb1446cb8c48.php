<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full bg-gray-100 dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent("title", config("app.name", "Rankolab")); ?> - Rankolab Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <link href="<?php echo e(asset('css/rankolab-admin.css')); ?>" rel="stylesheet">   
    
    
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Favicon -->
    
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>">
    
    
    <?php echo $__env->yieldPushContent('styles'); ?>
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
        <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900 p-6">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
            
            <!-- Footer -->
            
            
        </div>
    </div>
    
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
    
</body>
</html>

<?php /**PATH /home/ubuntu/rankolab_backend/resources/views/layouts/app.blade.php ENDPATH**/ ?>