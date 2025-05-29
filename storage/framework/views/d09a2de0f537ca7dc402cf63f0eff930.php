<?php $__env->startSection("content"); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Manage Payments</h1>
        <!-- Add filter/search options if needed -->
    </div>

    <?php echo $__env->make("partials.flash_messages", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subscription</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"><?php echo e($payment->id); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            <?php if($payment->user): ?>
                            <a href="<?php echo e(route("admin.users.show", $payment->user)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600"><?php echo e($payment->user->name); ?></a>
                            <?php else: ?>
                            N/A
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">$<?php echo e(number_format($payment->amount, 2)); ?> <?php echo e(strtoupper($payment->currency)); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php switch($payment->status):
                                    case ("succeeded"): ?> bg-green-100 text-green-800 <?php break; ?>
                                    <?php case ("pending"): ?>
                                    <?php case ("requires_payment_method"): ?>
                                    <?php case ("requires_confirmation"): ?>
                                    <?php case ("requires_action"): ?> bg-yellow-100 text-yellow-800 <?php break; ?>
                                    <?php case ("processing"): ?> bg-blue-100 text-blue-800 <?php break; ?>
                                    <?php case ("failed"): ?>
                                    <?php case ("canceled"): ?> bg-red-100 text-red-800 <?php break; ?>
                                    <?php default: ?> bg-gray-100 text-gray-800
                                <?php endswitch; ?>
                            ">
                                <?php echo e(ucfirst(str_replace("_", " ", $payment->status))); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            <?php if($payment->subscription): ?>
                            <a href="<?php echo e(route("admin.subscriptions.show", $payment->subscription)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">#<?php echo e($payment->subscription->id); ?> (<?php echo e($payment->subscription->plan->name ?? "N/A"); ?>)</a>
                            <?php else: ?>
                            N/A
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300"><?php echo e($payment->created_at->format("M d, Y H:i")); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?php echo e(route("admin.payments.show", $payment)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">View Details</a>
                            <!-- Add refund action if needed -->
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">No payments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700">
            <?php echo e($payments->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/rankolab_backend/resources/views/admin/payments/index.blade.php ENDPATH**/ ?>