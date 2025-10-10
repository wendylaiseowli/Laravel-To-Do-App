<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Project') }}
        </h2>
    </x-slot>
    <div class="bg-white p-6 mt-6">
        <form method="POST" action="{{route('create.project')}}">
            @csrf
            <div>
                <label for="project_name" class="font-medium">
                    Project Name
                </label>
                <input type="text" name="project_name" value="{{old('name')}}" id="project_name" class="mt-1 w-full border border-gray-300 rounded" placeholder="Project Name">
                @error('project_name')
                    <div class="text-red-600">{{$message}}</div>
                @enderror
            </div>
            
            <div class="mt-4">
                <label for="description" class="font-medium">
                    Project Description
                </label>
                <textarea name="description" id="description" class="mt-1 w-full border border-gray-300 rounded" placeholder="This project is for...">{{old('description')}}</textarea>
                @error('description')
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