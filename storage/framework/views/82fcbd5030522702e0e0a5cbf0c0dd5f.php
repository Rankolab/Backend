<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50 dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rankolab Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        :root {
            --color-primary: #3DD598;
            --color-primary-dark: #2BB583;
            --color-primary-light: #4EEAA9;
        }
        
        .bg-primary {
            background-color: var(--color-primary);
        }
        
        .hover\:bg-primary-dark:hover {
            background-color: var(--color-primary-dark);
        }
        
        .text-primary {
            color: var(--color-primary);
        }
        
        .hover\:text-primary-dark:hover {
            color: var(--color-primary-dark);
        }
        
        .focus\:ring-primary:focus {
            --tw-ring-color: var(--color-primary);
        }
        
        .focus\:border-primary:focus {
            border-color: var(--color-primary);
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-50 to-green-50 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 rounded-xl shadow-xl p-8">
            <div class="text-center">
                <img class="mx-auto h-24 w-auto" src="<?php echo e(asset('images/rankolab-logo.jpeg')); ?>" alt="Rankolab Logo">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                    Admin Login
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Sign in to your admin account
                </p>
            </div>
            
            <form class="mt-8 space-y-6" method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                
                <div class="rounded-md -space-y-px">
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Email Address
                        </label>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none relative block w-full px-3 py-3 border border-gray-300 dark:border-gray-700 placeholder-gray-500 text-gray-900 dark:text-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="admin@example.com" value="<?php echo e(old('email')); ?>">
                        
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Password
                        </label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="appearance-none relative block w-full px-3 py-3 border border-gray-300 dark:border-gray-700 placeholder-gray-500 text-gray-900 dark:text-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="••••••••">
                        
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" <?php echo e(old('remember') ? 'checked' : ''); ?>

                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 dark:border-gray-700 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                            Remember me
                        </label>
                    </div>

                    <?php if(Route::has('password.request')): ?>
                    <div class="text-sm">
                        <a href="<?php echo e(route('password.request')); ?>" class="font-medium text-primary hover:text-primary-dark">
                            Forgot your password?
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-150">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-primary-dark group-hover:text-primary-light" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Sign in
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                &copy; <?php echo e(date('Y')); ?> Rankolab. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /workspaces/Backend/resources/views/auth/login.blade.php ENDPATH**/ ?>