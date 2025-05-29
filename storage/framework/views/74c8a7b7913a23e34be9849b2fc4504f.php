<?php $__env->startSection("content"); ?>
<div class="container-fluid">
    <h1 class="mt-4">Add New Affiliate</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route("admin.dashboard")); ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route("admin.affiliates.index")); ?>">Affiliates</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus-circle me-1"></i>
            Create Affiliate Record
        </div>
        <div class="card-body">
            <?php echo $__env->make("partials.validation_errors", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <form action="<?php echo e(route("admin.affiliates.store")); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label for="user_id" class="form-label">User</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        <option value="">Select User</option>
                        
                        <?php $__currentLoopData = \App\Models\User::orderBy("name")->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(old("user_id") == $user->id ? "selected" : ""); ?>>
                                <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="commission_rate" class="form-label">Commission Rate (%)</label>
                    <input type="number" class="form-control" id="commission_rate" name="commission_rate" value="<?php echo e(old("commission_rate", 10)); ?>" min="0" max="100" step="0.1" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="active" <?php echo e(old("status", "active") == "active" ? "selected" : ""); ?>>Active</option>
                        <option value="inactive" <?php echo e(old("status") == "inactive" ? "selected" : ""); ?>>Inactive</option>
                    </select>
                </div>

                

                <button type="submit" class="btn btn-primary">Create Affiliate</button>
                <a href="<?php echo e(route("admin.affiliates.index")); ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/rankolab_backend/resources/views/admin/affiliates/create.blade.php ENDPATH**/ ?>