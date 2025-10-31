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
            <?php echo e(__('Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
    <?php if(session('success')): ?>
    <div class="p-4 text-center">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <?php echo e(__("You're logged in!")); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl">Projects</h1>
            <a href="<?php echo e(route('new.project')); ?>" >+ Create New Project</a>
        </div>

        <?php if($projects && $projects->count()): ?>
            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white shadow rounded p-4 mb-6">
                    
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-xl font-semibold">
                            <a href="<?php echo e(route('show.project', $project->id)); ?>">
                                <?php echo e($project->project_name); ?>

                            </a>
                        </h2>
                        <div class="flex">
                            <a href="<?php echo e(route('show.edit.project', $project->id)); ?>" 
                            class="px-2 py-1 border rounded">
                                Edit
                            </a>

                            <form method="POST" action="<?php echo e(route('delete.project', $project->id)); ?>" 
                                onsubmit="return confirm('Are you sure you want to delete the project? The tasks that include in this project will also be deleted')"
                                class="flex">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 px-2 py-1 border rounded">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-gray-700 mb-2"><?php echo e($project->description ?? 'No description'); ?></p>

                    <?php if($project->tasks && $project->tasks->count()): ?>
                        <div class="text-sm text-gray-500">
                            <span>Total tasks: <?php echo e($project->tasks->count()); ?></span>
                            <span>Completed: <?php echo e($project->tasks->where('status', 'completed')->count()); ?></span>
                            <span>Pending: <?php echo e($project->tasks->where('status', 'pending')->count()); ?></span>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">Total tasks: 0</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <p class="text-gray-500">No projects yet.</p>
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
<?php endif; ?>




<?php /**PATH C:\Users\User\Documents\laravel project\myproject\resources\views/dashboard.blade.php ENDPATH**/ ?>