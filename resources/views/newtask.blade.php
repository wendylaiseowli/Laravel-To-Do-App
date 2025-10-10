    <x-app-layout>    
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('New Task') }}
            </h2>
        </x-slot>

    <div class="bg-white p-6 mt-6">
        <form method="POST" action="{{route('store.task', $project->id)}}">
            @csrf
            <div>
                <label for="title" class="font-medium">
                    Task Name
                </label>
                <input type="text" name="title" value="{{old('title')}}" class="mt-1 w-full border border-gray-300 rounded" placeholder="Task name">
                @error('title')
                    <div class="text-red-600">{{$message}}</div>
                @enderror
            </div>

            <div class="mt-4">
                <label for="priority" class="font-medium">
                    Priority
                </label>
                <select name="priority" class="mt-1 w-full border border-gray-300 rounded">
                    <option disabled selected value="">Select an option</option>
                    <option value="low" {{old('priority') == 'low' ? 'selected' : ''}}>Low</option>
                    <option value="medium" {{old('priority') == 'medium' ? 'selected': ''}}>Medium</option>
                    <option value="high" {{old('priority')== 'high' ? 'selected' : ''}}>High</option>
                </select>
                @error('priority')
                    <div class="text-red-600">{{$message}}</div>
                @enderror
            </div>

            <div class="mt-4">
                <label for="due_date" class="font-medium">
                    Due date
                </label>
                    <input type="date" name="due_date" value="{{old('due_date')}}" class="mt-1 w-full border border-gray-300 rounded">
                @error('due_date')
                    <div class="text-red-600">{{$message}}</div>
                @enderror
            </div>

            <div class="mt-4">
                <label for="description" class="font-medium">
                    Task Description
                </label>
                <textarea name="description" id="description" class="mt-1 w-full border border-gray-300 rounded" placeholder="This task is for...">{{old('description')}}</textarea>
                @error('description')
                    <div class="text-red-600">{{$message}}</div>
                @enderror
            </div>

            <div class="mt-3">
                <label for="status" class="font-medium">
                    Status
                </label>
                <select name="status" class="mt-1 w-full border border-gray-300 rounded">
                    <option disabled selected value="" >Select an option</option>
                    <option value="pending" {{old('status') == 'pending' ? 'selected': ''}}>Pending</option>
                    <option value="completed" {{old('status') == 'completed'? 'selected': ''}}>Completed</option>
                </select>
                @error('status')
                    <div class="text-red-600">{{$message}}</div>
                @enderror
            </div>

            
            <div class="mt-3 flex justify-end">
                <button type="submit" class="border px-3 rounded">
                    Create
                </button>
            </div>
        </form>
    </div>
</x-app-layout> 