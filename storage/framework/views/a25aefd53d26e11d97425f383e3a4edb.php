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
            <?php echo e(__('Edit Task')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="bg-white p-6 mt-6">
        <form method="POST" action="<?php echo e(route('edit.task', ['project' => $project->id, 'task' => $task->id])); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div>
                <label for="title" class="font-medium">
                    Task Name
                </label>
                <input type="text" name="title" id="title" value="<?php echo e(old('title', $task->title)); ?>"
                    class="mt-1 w-full border border-gray-300 rounded" placeholder="Task name">
                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-600"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mt-4">
                <label for="priority" class="font-medium">
                    Priority
                </label>
                <select name="priority" id="priority"
                    class="mt-1 w-full border border-gray-300 rounded">
                    <option disabled selected value="">Select an option</option>
                    <option value="low" <?php echo e(old('priority', $task->priority)=='low' ? 'selected' : ''); ?>>Low</option>
                    <option value="medium" <?php echo e(old('priority', $task->priority)=='medium' ? 'selected' : ''); ?>>Medium</option>
                    <option value="high" <?php echo e(old('priority', $task->priority)=='high' ? 'selected' : ''); ?>>High</option>
                </select>
                <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-600"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mt-4">
                <label for="due_date" class="font-medium">
                    Due Date    
                </label>
                <input type="date" name="due_date" id="due_date" value="<?php echo e(old('due_date', $task->due_date)); ?>"
                    class="mt-1 w-full border border-gray-300 rounded">
                <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-600"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mt-4">
                <label for="description" class="font-medium">
                    Task Description
                </label>
                <textarea name="description" id="description"
                    class="mt-1 w-full border border-gray-300 rounded" placeholder="This task is for..."><?php echo e(old('description', $task->description)); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-600"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mt-3">
                <label for="status" class="font-medium">
                    Status
                </label>
                <select name="status" id="status"
                    class="mt-1 w-full border border-gray-300 rounded">
                    <option disabled selected value="">
                        Select an option
                    </option>
                    <option value="pending" <?php echo e(old('status', $task->status)=='pending' ? 'selected' : ''); ?>>
                        Pending
                    </option>
                    <option value="completed" <?php echo e(old('status', $task->status)=='completed' ? 'selected' : ''); ?>>
                        Completed
                    </option>
                </select>
                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-red-600"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mt-3 flex justify-end">
                <button type="submit" class="border px-3 rounded">
                    Save Changes
                </button>
            </div>
        </form>
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
<?php /**PATH C:\Users\User\Documents\laravel project\myproject\resources\views/edittask.blade.php ENDPATH**/ ?>