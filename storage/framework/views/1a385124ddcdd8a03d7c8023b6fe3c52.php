<?php $__env->startSection("title", "Create New License"); ?>

<?php $__env->startPush("styles"); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create New License</h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="<?php echo e(route("admin.dashboard")); ?>" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li><a href="<?php echo e(route("admin.licenses.index")); ?>" class="hover:text-gray-700 dark:hover:text-gray-200">Licenses</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Create License</li>
            </ol>
        </nav>
    </div>

    <!-- Validation Errors -->
    <?php echo $__env->make("partials.validation_errors", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Create License Form -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form method="POST" action="<?php echo e(route("admin.licenses.store")); ?>">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- License Key -->
                <div>
                    <label for="key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">License Key <span class="text-red-500">*</span></label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" name="key" id="key" value="<?php echo e(old("key", $generatedKey ?? \Illuminate\Support\Str::uuid()->toString())); ?>" required readonly
                               class="flex-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                        <button type="button" onclick="generateNewKey()" class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-400 text-sm hover:bg-gray-100 dark:hover:bg-gray-500">
                            Generate New
                        </button>
                    </div>
                     <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">A unique key will be generated. You can regenerate if needed.</p>
                    <?php $__errorArgs = ["key"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- User Assignment -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign to User (Optional)</label>
                    <select name="user_id" id="user_id"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="">-- No User --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(old("user_id") == $user->id ? "selected" : ""); ?>><?php echo e($user->name); ?> (<?php echo e($user->email); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ["user_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- License Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">License Type <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border <?php echo e($errors->has("type") ? "border-red-500" : "border-gray-300 dark:border-gray-600"); ?> focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                        
                        <option value="standard" <?php echo e(old("type", "standard") == "standard" ? "selected" : ""); ?>>Standard</option>
                        <option value="premium" <?php echo e(old("type") == "premium" ? "selected" : ""); ?>>Premium</option>
                        <option value="lifetime" <?php echo e(old("type") == "lifetime" ? "selected" : ""); ?>>Lifetime</option>
                        <option value="trial" <?php echo e(old("type") == "trial" ? "selected" : ""); ?>>Trial</option>
                    </select>
                    <?php $__errorArgs = ["type"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border <?php echo e($errors->has("status") ? "border-red-500" : "border-gray-300 dark:border-gray-600"); ?> focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="active" <?php echo e(old("status", "active") == "active" ? "selected" : ""); ?>>Active</option>
                        <option value="inactive" <?php echo e(old("status") == "inactive" ? "selected" : ""); ?>>Inactive</option>
                        <option value="pending" <?php echo e(old("status") == "pending" ? "selected" : ""); ?>>Pending</option>
                        <option value="expired" <?php echo e(old("status") == "expired" ? "selected" : ""); ?>>Expired</option>
                    </select>
                    <?php $__errorArgs = ["status"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Expiration Date -->
                <div class="md:col-span-1">
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expiration Date (Optional)</label>
                    <input type="date" name="expires_at" id="expires_at" value="<?php echo e(old("expires_at")); ?>"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Leave blank for non-expiring licenses (e.g., lifetime).</p>
                    <?php $__errorArgs = ["expires_at"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Allowed Domains -->
                 <div class="md:col-span-1">
                    <label for="allowed_domains" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Allowed Domains</label>
                    <input type="number" name="allowed_domains" id="allowed_domains" value="<?php echo e(old("allowed_domains", 1)); ?>" min="1"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Number of domains this license can be activated on.</p>
                    <?php $__errorArgs = ["allowed_domains"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="<?php echo e(route("admin.licenses.index")); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Create License
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>
<script>
    function generateNewKey() {
        // Basic UUID generation in JS (consider a more robust method if needed)
        const newKey = ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
        document.getElementById("key").value = newKey;
    }
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/rankolab_backend/resources/views/admin/licenses/create.blade.php ENDPATH**/ ?>