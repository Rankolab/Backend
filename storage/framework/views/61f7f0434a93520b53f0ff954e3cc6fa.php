<?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 dark:bg-red-900 dark:border-red-700 dark:text-red-200" role="alert">
        <strong class="font-bold">Whoops! Something went wrong.</strong>
        <span class="block sm:inline">Please check the form below for errors.</span>
        <ul class="mt-3 list-disc list-inside text-sm">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<?php /**PATH /home/ubuntu/rankolab_backend/resources/views/partials/validation_errors.blade.php ENDPATH**/ ?>