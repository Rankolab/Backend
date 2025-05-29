<?php if(session("success")): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline"><?php echo e(session("success")); ?></span>
    </div>
<?php endif; ?>

<?php if(session("error")): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline"><?php echo e(session("error")); ?></span>
    </div>
<?php endif; ?>

<?php if(session("warning")): ?>
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Warning!</strong>
        <span class="block sm:inline"><?php echo e(session("warning")); ?></span>
    </div>
<?php endif; ?>

<?php if(session("info")): ?>
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Info:</strong>
        <span class="block sm:inline"><?php echo e(session("info")); ?></span>
    </div>
<?php endif; ?>


<?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Validation Errors:</strong>
        <ul class="mt-2 list-disc list-inside">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH /home/ubuntu/rankolab_backend/resources/views/partials/flash_messages.blade.php ENDPATH**/ ?>