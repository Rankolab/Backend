<?php $__env->startSection("title", "Manage API Keys"); ?>

<?php $__env->startPush("styles"); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">API Key Management</h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="<?php echo e(route("admin.dashboard")); ?>" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li><a href="<?php echo e(route("admin.api.monitoring")); ?>" class="hover:text-gray-700 dark:hover:text-gray-200">API</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Keys</li>
            </ol>
        </nav>
    </div>

    <!-- Session Messages -->
    <?php echo $__env->make("partials.session_messages", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- API Keys Form -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Update API Keys</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Update the API keys used by the system to interact with external services.</p>

        <form method="POST" action="<?php echo e(route("admin.api.keys.update")); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field("POST"); ?> 

            <div class="space-y-6">
                <?php $__empty_1 = true; $__currentLoopData = $apiServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <div class="md:col-span-1">
                            <label for="api_key_<?php echo e($service->id); ?>" class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e($service->name); ?> Key</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Category: <?php echo e($service->category); ?></p>
                        </div>
                        <div class="md:col-span-2">
                            <input type="password" name="api_keys[<?php echo e($service->id); ?>]" id="api_key_<?php echo e($service->id); ?>" 
                                   value="<?php echo e(old("api_keys.".$service->id, $service->api_key ? "********" : "")); ?>" 
                                   placeholder="Enter new API key for <?php echo e($service->name); ?>"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                            
                            <?php $__errorArgs = ["api_keys.".$service->id];
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No API services configured.</p>
                <?php endif; ?>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Update Keys
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>

<?php $__env->stopPush(); ?>


<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/rankolab_backend/resources/views/admin/api/keys.blade.php ENDPATH**/ ?>