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
            <?php echo e(__('Project')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
    <?php if(session('success')): ?>
        <?php echo e(session('success')); ?>

    <?php endif; ?>
    <div class="flex items-center justify-between bg-white p-4 mt-2 shadow">
        <p class="font-semibold">Filter</p>
        <form method="GET" action=<?php echo e(route('show.project', $project->id)); ?>>
            <select name="status" onchange="this.form.submit()">
                <option selected value="">Please select</option>
                <option value="pending" <?php echo e(request('status')== 'pending' ? 'selected': ''); ?>>Pending</option>
                <option value="completed" <?php echo e(request('status') =='completed' ? 'selected': ''); ?>>Completed</option>
            </select>
            <select name="due_date" onchange="this.form.submit()">
                <option selected value="">Please select</option>
                <option value="due_today" <?php echo e(request('due_date') =='due_today'? 'selected' : ''); ?>>Due today</option>
                <option value="this_week" <?php echo e(request('due_date')== 'this_week' ? 'selected' : ''); ?>>This week</option>
                <option value="over_due" <?php echo e(request('due_date')== 'over_due' ? 'selected': ''); ?>>Over due</option>
            </select>
        </form>
    </div>
    <div class="flex justify-end p-4">
        <p><a href="<?php echo e(route('new.task', $project->id)); ?>">+ Create Task</a></p>
    </div>
    <div class="p-6 <?php echo e((!$tasks || $tasks->count()=== 0) ? 'bg-white': ''); ?>">
        <div class="p-4">
            <h2 class="text-xl font-semibold">Project Name: <?php echo e($project->project_name); ?></h2>
            <p>Description: <?php echo e($project->description); ?></p>
        </div>
        <?php if($tasks && $tasks->count()): ?>
            <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border shadow p-6 mb-6 bg-white">
                <div class=" flex justify-between item-center">
                    <h1 class="text-xl font-semibold">Task title: <?php echo e($task->title); ?></h1>
                    <div class="flex">
                        <a href="<?php echo e(route('show.edit.task', ['project' => $project, 'task' => $task])); ?>" class="px-2 py-1 border rounded">Edit</a>
                        <form method="POST" action="<?php echo e(route('delete.task', ['project'=>$project->id, 'task'=>$task->id])); ?>" onsubmit="return confirm('Are you sure u want to delete the task?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 px-2 py-1 border rounded">Delete</button>
                        </form>
                    </div>
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <p class="text-gray-500 text-sm p-4">No task created yet..</p>
        <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\Users\User\Documents\laravel project\myproject\resources\views/project.blade.php ENDPATH**/ ?>