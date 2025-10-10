<x-app-layout>    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Project') }}
        </h2>
    </x-slot>
    <div class="flex items-center justify-between bg-white p-4 mt-2 shadow">
        <p class="font-semibold">Filter</p>
        <form method="GET" action={{route('show.project', $project->id)}}>
            <select name="status" onchange="this.form.submit()">
                <option selected value="">Please select</option>
                <option value="pending" {{request('status')== 'pending' ? 'selected': ''}}>Pending</option>
                <option value="completed" {{request('status') =='completed' ? 'selected': ''}}>Completed</option>
            </select>
            <select name="due_date" onchange="this.form.submit()">
                <option selected value="">Please select</option>
                <option value="due_today" {{request('due_date') =='due_today'? 'selected' : ''}}>Due today</option>
                <option value="this_week" {{request('due_date')== 'this_week' ? 'selected' : ''}}>This week</option>
                <option value="over_due" {{request('due_date')== 'over_due' ? 'selected': ''}}>Over due</option>
            </select>
        </form>
    </div>
    <div class="flex justify-end p-4">
        <p><a href="{{route('new.task', $project->id)}}">+ Create Task</a></p>
    </div>
    <div class="p-6 {{(!$tasks || $tasks->count()=== 0) ? 'bg-white': ''}}">
        <div class="p-4">
            <h2 class="text-xl font-semibold">Project Name: {{$project->project_name}}</h2>
            <p>Description: {{$project->description}}</p>
        </div>
        @if($tasks && $tasks->count())
            @foreach ($tasks as $task)
            <div class="border shadow p-6 mb-6 bg-white">
                <div class=" flex justify-between item-center">
                    <h1 class="text-xl font-semibold">Task title: {{$task->title}}</h1>
                    <div class="flex">
                        <a href="{{ route('show.edit.task', ['project' => $project, 'task' => $task]) }}" class="px-2 py-1 border rounded">Edit</a>
                        <form method="POST" action="{{route('delete.task', ['project'=>$project->id, 'task'=>$task->id])}}" onsubmit="return confirm('Are you sure u want to delete the task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 px-2 py-1 border rounded">Delete</button>
                        </form>
                    </div>
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
            @endforeach
        @else
            <p class="text-gray-500 text-sm p-4">No task created yet..</p>
        @endif
    </div>
</x-app-layout>