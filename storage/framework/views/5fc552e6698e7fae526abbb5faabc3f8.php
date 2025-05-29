<?php $__env->startSection("title", "Manage Users"); ?>

<?php $__env->startPush("styles"); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Users Management</h1>
            <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex space-x-2">
                    <li><a href="<?php echo e(route("admin.dashboard")); ?>" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                    <li><span>&gt;</span></li>
                    <li class="text-gray-700 dark:text-gray-200" aria-current="page">Users</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route("admin.users.create")); ?>" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-1"></i> Add User
            </a>
        </div>
    </div>

    <!-- Session Messages -->
    <?php echo $__env->make("partials.session_messages", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 mb-6">
        <form method="GET" action="<?php echo e(route("admin.users.index")); ?>" id="filter-form">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                    <input type="text" name="search" id="search" value="<?php echo e(request("search")); ?>" placeholder="Name or Email..."
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Role Filter -->
                <div>
                    <?php $roles = \App\Models\Role::orderBy("name")->get(); ?>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                    <select name="role" id="role"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="">All Roles</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role->id); ?>" <?php echo e(request("role") == $role->id ? "selected" : ""); ?>><?php echo e($role->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Status Filter (Assuming you add a status column to users table) -->
                

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="<?php echo e(route("admin.users.index")); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Form -->
    <form method="POST" action="<?php echo e(route("admin.users.bulk_action")); ?>" id="bulk-action-form" class="mb-6">
        <?php echo csrf_field(); ?>
        <div class="flex items-center space-x-4 bg-gray-100 dark:bg-gray-700 p-3 rounded-md shadow">
            <label for="bulk_action" class="text-sm font-medium text-gray-700 dark:text-gray-300">With selected:</label>
            <select name="action" id="bulk_action" required
                    class="block w-auto pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-800 dark:text-white">
                <option value="">Choose Action...</option>
                <option value="delete">Delete Selected</option>
                
                
                
            </select>
            
            <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150" onclick="return confirm("Are you sure you want to perform this bulk action?");">
                Apply
            </button>
            <span id="selection-count" class="text-sm text-gray-600 dark:text-gray-400 ml-auto">0 selected</span>
        </div>
        
        <input type="hidden" name="selected_users" id="selected_users">
    </form>

    <!-- Users Table -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left">
                            <input type="checkbox" id="select-all-checkbox"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roles</th>
                        
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joined</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <input type="checkbox" name="user_ids[]" value="<?php echo e($user->id); ?>"
                                       class="user-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"><?php echo e($user->name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300"><?php echo e($user->email); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                <?php $__empty_2 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100 mr-1"><?php echo e($role->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <span class="text-xs text-gray-400">No roles</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300"><?php echo e($user->created_at->format("Y-m-d")); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                <a href="<?php echo e(route("admin.users.show", $user->id)); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route("admin.users.edit", $user->id)); ?>" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo e(route("admin.users.destroy", $user->id)); ?>" class="inline-block" onsubmit="return confirm("Are you sure you want to delete this user? This action cannot be undone.");">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field("DELETE"); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                No users found matching your criteria.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <?php if($users->hasPages()): ?>
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                <?php echo e($users->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const selectAllCheckbox = document.getElementById("select-all-checkbox");
    const userCheckboxes = document.querySelectorAll(".user-checkbox");
    const bulkActionForm = document.getElementById("bulk-action-form");
    const selectedUsersInput = document.getElementById("selected_users");
    const selectionCountSpan = document.getElementById("selection-count");

    function updateSelectionCount() {
        const selectedCount = document.querySelectorAll(".user-checkbox:checked").length;
        selectionCountSpan.textContent = `${selectedCount} selected`;
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectionCount();
        });
    }

    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function() {
            if (!checkbox.checked) {
                selectAllCheckbox.checked = false;
            }
            // Check if all checkboxes are checked
            else if (document.querySelectorAll(".user-checkbox:checked").length === userCheckboxes.length) {
                selectAllCheckbox.checked = true;
            }
            updateSelectionCount();
        });
    });

    if (bulkActionForm) {
        bulkActionForm.addEventListener("submit", function(e) {
            const selectedIds = Array.from(document.querySelectorAll(".user-checkbox:checked"))
                                   .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert("Please select at least one user to perform a bulk action.");
                e.preventDefault(); // Prevent form submission
                return;
            }

            selectedUsersInput.value = JSON.stringify(selectedIds);
            // Confirmation is handled by the onclick attribute on the button
        });
    }

    // Initial count update on page load
    updateSelectionCount();
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/rankolab_backend/resources/views/admin/users/index.blade.php ENDPATH**/ ?>