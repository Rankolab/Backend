<?php $__env->startSection("title", "Application Settings"); ?>

<?php $__env->startPush("styles"); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Application Settings</h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="<?php echo e(route("admin.dashboard")); ?>" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Settings</li>
            </ol>
        </nav>
    </div>

    <!-- Session Messages -->
    <?php echo $__env->make("partials.session_messages", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Settings Form -->
    <form method="POST" action="<?php echo e(route("admin.settings.update")); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field("POST"); ?> 

        <div class="space-y-8">
            <?php $__empty_1 = true; $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $groupSettings): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 capitalize"><?php echo e(str_replace("_", " ", $group)); ?> Settings</h2>
                    <div class="space-y-6">
                        <?php $__currentLoopData = $groupSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div class="md:col-span-1">
                                    <label for="setting_<?php echo e($setting->key); ?>" class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(ucwords(str_replace("_", " ", $setting->key))); ?></label>
                                    <?php if($setting->description): ?>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400"><?php echo e($setting->description); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="md:col-span-2">
                                    <?php if($setting->type === "boolean"): ?>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="settings[<?php echo e($setting->key); ?>]" id="setting_<?php echo e($setting->key); ?>" value="true" 
                                                   class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                                   <?php echo e(old("settings.".$setting->key, $setting->value) == "true" ? "checked" : ""); ?>>
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Enable</span>
                                        </label>
                                    <?php elseif($setting->type === "textarea"): ?>
                                        <textarea name="settings[<?php echo e($setting->key); ?>]" id="setting_<?php echo e($setting->key); ?>" rows="3"
                                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white"><?php echo e(old("settings.".$setting->key, $setting->value)); ?></textarea>
                                    <?php elseif($setting->type === "integer"): ?>
                                         <input type="number" name="settings[<?php echo e($setting->key); ?>]" id="setting_<?php echo e($setting->key); ?>" 
                                               value="<?php echo e(old("settings.".$setting->key, $setting->value)); ?>" 
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                                    <?php else: ?> 
                                        <input type="text" name="settings[<?php echo e($setting->key); ?>]" id="setting_<?php echo e($setting->key); ?>" 
                                               value="<?php echo e(old("settings.".$setting->key, $setting->value)); ?>" 
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                                    <?php endif; ?>
                                    <?php $__errorArgs = ["settings.".$setting->key];
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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <p class="text-center text-gray-500 dark:text-gray-400">No settings found.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Form Actions -->
        <div class="mt-8 flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                Save Settings
            </button>
        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>

<?php $__env->stopPush(); ?>


<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/rankolab_backend/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>