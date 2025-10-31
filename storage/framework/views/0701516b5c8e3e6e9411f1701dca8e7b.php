<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Task')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
    <div class="bg-white p-4 shadow mt-4">
        <div class="mb-4">
            <h1 class="text-xl font-semibold">Task title: <?php echo e($task->title); ?></h1>
        </div>
        <p>Description: <?php echo e($task->description ? $task->description: ' -'); ?></p>
        <p>Status: <?php echo e($task->status); ?>

        <?php if($task->status=='completed'): ?>
           (<?php echo e($task->completed_at->timezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s')); ?>)
        <?php endif; ?>
        </p>
        <p>Priority: <?php echo e($task->priority); ?></p>
        <p>Duedate: <?php echo e($task->due_date ? $task->due_date->format('Y-m-d'): ' -'); ?></p>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\User\Documents\laravel project\myproject\resources\views/task.blade.php ENDPATH**/ ?>