@extends('layouts.app')

@section('content')
<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="sm:flex sm:items-center justify-between">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold leading-6 text-gray-900">Tasks</h1>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('tasks.create') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Task</a>
            </div>
        </div>
    
        <div class="mt-8">
            <form action="{{ route('tasks.index') }}" method="GET">
                <div class="flex flex-wrap items-end">
                    <div class="w-full sm:w-auto mb-3 sm:mb-0">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status:</label>
                        <select name="status" id="status" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
    
                    <div class="w-full sm:w-auto mb-3 sm:mb-0 sm:ml-3">
                        <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                        <select name="category" id="category" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            <option value="">All</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="w-full sm:w-auto mb-3 sm:mb-0 sm:ml-3">
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priority:</label>
                        <select name="priority" id="priority" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            <option value="">All</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
    
                    <div class="ml-4">
                        <button type="submit" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>
    
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th scope="col" class="pl-4 pr-3 py-3.5 text-left text-xs font-medium text-gray-900 sm:pl-0">Title</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-900">Description</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-900">Due Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-900">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-900">Category</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium text-gray-900">Priority</th>
                                <th scope="col" class="relative pr-4 py-3.5">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($tasks as $task)
                            <tr>
                                <td class="pl-4 pr-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sm:pl-0">{{ $task->title }}</td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">{{ $task->description }}</td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">{{ $task->due_date }}</td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">{{ $task->status }}</td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($task->category)
                                        {{ $task->category->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">{{ $task->priority }}</td>
                                <td class="pr-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-indigo-700 hover:text-indigo-900">View</a>
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-yellow-600 hover:text-yellow-900 ml-2">Edit</a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection