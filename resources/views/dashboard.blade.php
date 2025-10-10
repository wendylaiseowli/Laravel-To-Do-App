<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

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


{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Create Project Button -->
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">Projects</h1>
                <a href="{{ route('new.project') }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Create New Project
                </a>
            </div>

            <!-- Projects List -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if($projects->count())
                    <div class="grid gap-6">
                        @foreach ($projects as $project)
                            <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-start hover:shadow-md transition">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">{{ $project->project_name }}</h2>
                                    <p class="text-gray-600 mt-1">{{ $project->description }}</p>
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('show.edit.project', $project->id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>

                                    <form method="POST" action="{{ route('delete.project', $project->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 font-medium"
                                                onclick="return confirm('Are you sure you want to delete this project?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-center py-6">No projects yet. Start by creating one!</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout> --}}

