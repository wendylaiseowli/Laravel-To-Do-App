<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    @if (session('success'))
        {{ session('success') }}
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl">Projects</h1>
            <a href="{{ route('new.project') }}" >+ Create New Project</a>
        </div>

        @if($projects && $projects->count())
            @foreach ($projects as $project)
                <div class="bg-white shadow rounded p-4 mb-6">
                    
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-xl font-semibold">
                            <a href="{{ route('show.project', $project->id) }}">
                                {{ $project->project_name }}
                            </a>
                        </h2>
                        <div class="flex">
                            <a href="{{ route('show.edit.project', $project->id) }}" 
                            class="px-2 py-1 border rounded">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('delete.project', $project->id) }}" 
                                onsubmit="return confirm('Are you sure you want to delete the project? The tasks that include in this project will also be deleted')"
                                class="flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 px-2 py-1 border rounded">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-gray-700 mb-2">{{ $project->description ?? 'No description' }}</p>

                    @if($project->tasks && $project->tasks->count())
                        <div class="text-sm text-gray-500">
                            <span>Total tasks: {{ $project->tasks->count() }}</span>
                            <span>Completed: {{ $project->tasks->where('status', 'completed')->count() }}</span>
                            <span>Pending: {{ $project->tasks->where('status', 'pending')->count() }}</span>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Total tasks: 0</p>
                    @endif
                </div>
            @endforeach
        @else
            <p class="text-gray-500">No projects yet.</p>
        @endif
    </div>
</x-app-layout>