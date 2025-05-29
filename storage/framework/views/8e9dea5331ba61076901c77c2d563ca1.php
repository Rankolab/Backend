<?php if(session("success")): ?>
    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-check-circle mr-3"></i></div>
            <div>
                <p class="font-bold">Success</p>
                <p class="text-sm"><?php echo e(session("success")); ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(session("error")): ?>
    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-times-circle mr-3"></i></div>
            <div>
                <p class="font-bold">Error</p>
                <p class="text-sm"><?php echo e(session("error")); ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(session("warning")): ?>
    <div class="mb-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md shadow-sm" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-exclamation-triangle mr-3"></i></div>
            <div>
                <p class="font-bold">Warning</p>
                <p class="text-sm"><?php echo e(session("warning")); ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(session("info")): ?>
    <div class="mb-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md shadow-sm" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-info-circle mr-3"></i></div>
            <div>
                <p class="font-bold">Info</p>
                <p class="text-sm"><?php echo e(session("info")); ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>


<?php if($errors->any()): ?>
    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-exclamation-circle mr-3"></i></div>
            <div>
                <p class="font-bold">Validation Errors</p>
                <ul class="mt-1 list-disc list-inside text-sm">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php /**PATH /home/ubuntu/rankolab_backend/resources/views/partials/session_messages.blade.php ENDPATH**/ ?>