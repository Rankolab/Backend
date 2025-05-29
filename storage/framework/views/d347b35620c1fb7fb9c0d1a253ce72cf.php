<!-- Header -->
<header class="flex items-center justify-between h-16 px-6 bg-white dark:bg-gray-800 border-b dark:border-gray-700">
    <!-- Mobile Menu Button -->
    <button 
        @click="toggleSidebar"
        class="text-gray-500 dark:text-gray-400 focus:outline-none focus:text-gray-600 dark:focus:text-gray-300 lg:hidden"
    >
        <i class="fas fa-bars w-6 h-6"></i>
    </button>

    <!-- Search (Optional Placeholder) -->
    <div class="relative hidden md:block">
        
    </div>

    <!-- Right Side Header -->
    <div class="flex items-center space-x-4">
        <!-- Dark Mode Toggle -->
        <button 
            @click="toggleDarkMode"
            class="p-2 text-gray-500 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring"
        >
            <i class="fas" :class="{ "fa-moon": !darkMode, "fa-sun": darkMode }"></i>
        </button>

        <!-- Notifications (Optional Placeholder) -->
        

        <!-- User Dropdown -->
        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 focus:outline-none">
                <span class="text-gray-700 dark:text-gray-300"><?php echo e(Auth::user()->name ?? "Admin User"); ?></span>
                
                
                <i class="fas fa-chevron-down text-gray-500 dark:text-gray-400"></i>
            </button>

            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" 
                 class="absolute right-0 w-48 mt-2 py-2 bg-white dark:bg-gray-800 rounded-md shadow-xl z-20"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 x-cloak
            >
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
                <form method="POST" action="<?php echo e(route("logout")); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>

<?php /**PATH /home/ubuntu/rankolab_backend/resources/views/partials/header.blade.php ENDPATH**/ ?>