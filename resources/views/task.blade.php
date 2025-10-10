<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task') }}
        </h2>
    </x-slot>
    <div class="bg-white p-4 shadow mt-4">
        <div class="mb-4">
            <h1 class="text-xl font-semibold">Task title: {{$task->title}}</h1>
        </div>
        <p>Description: {{$task->description ? $task->description: ' -'}}</p>
        <p>Status: {{$task->status}}
        @if($task->status=='completed')
           ({{ $task->completed_at->timezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }})
        @endif
        </p>
        <p>Priority: {{$task->priority}}</p>
        <p>Duedate: {{$task->due_date ? $task->due_date->format('Y-m-d'): ' -'}}</p>
    </div>
</x-app-layout>